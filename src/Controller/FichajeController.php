<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\VolunteerService;
use App\Repository\VolunteerRepository;
use App\Entity\Fichaje;
use App\Repository\FichajeRepository;
use App\Repository\VolunteerServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FichajeController extends AbstractController
{
    #[Route('/service/{id}/fichaje', name: 'app_fichaje', methods: ['POST'])]
    public function fichaje(Request $request, Service $service, EntityManagerInterface $entityManager, VolunteerRepository $volunteerRepository, VolunteerServiceRepository $volunteerServiceRepository): Response
    {
        $data = $request->request->all();

        $startTimeStr = ($data['start-date'] ?? '') . ' ' . ($data['start-time'] ?? '');
        $endTimeStr = ($data['end-date'] ?? '') . ' ' . ($data['end-time'] ?? '');

        if (trim($startTimeStr) === '' || trim($endTimeStr) === '') {
            $this->addFlash('error', 'La fecha y hora de inicio y fin son obligatorias.');
            return $this->redirectToRoute('app_service_edit', ['id' => $service->getId()]);
        }

        $startTime = new \DateTime($startTimeStr);
        $endTime = new \DateTime($endTimeStr);
        $volunteerIds = $data['volunteers'] ?? [];

        if ($endTime < $startTime) {
            $this->addFlash('error', 'La hora de fin no puede ser anterior a la hora de inicio.');
            return $this->redirectToRoute('app_service_edit', ['id' => $service->getId()]);
        }

        $duration = $endTime->getTimestamp() - $startTime->getTimestamp();
        $durationInMinutes = round($duration / 60);

        // --- NEW CLEANUP LOGIC ---
        // First, remove all existing fichaje records for the entire service.
        $allVolunteerServices = $service->getVolunteerServices();
        foreach ($allVolunteerServices as $vs) {
            foreach ($vs->getFichajes() as $fichaje) {
                $entityManager->remove($fichaje);
            }
        }
        // --- END NEW CLEANUP LOGIC ---

        foreach ($volunteerIds as $volunteerId) {
            $volunteer = $volunteerRepository->find($volunteerId);
            if ($volunteer) {
                $volunteerService = $volunteerServiceRepository->findOneBy(['volunteer' => $volunteer, 'service' => $service]);
                if (!$volunteerService) {
                    // This case should ideally not be hit if volunteer is in the list,
                    // but we handle it for robustness.
                    $volunteerService = new VolunteerService();
                    $volunteerService->setVolunteer($volunteer);
                    $volunteerService->setService($service);
                    $entityManager->persist($volunteerService);
                }

                // Create a new Fichaje record with the mass clock-in/out times
                $fichaje = new Fichaje();
                $fichaje->setVolunteerService($volunteerService);
                $fichaje->setStartTime($startTime);
                $fichaje->setEndTime($endTime);

                $entityManager->persist($fichaje);
            }
        }

        $entityManager->flush();

        $this->addFlash('success', 'Fichaje guardado correctamente.');

        return $this->redirectToRoute('app_service_edit', ['id' => $service->getId(), '_fragment' => 'asistencias']);
    }

    #[Route('/volunteer_service/{id}/clockin', name: 'app_fichaje_clockin', methods: ['POST'])]
    public function clockIn(Request $request, VolunteerService $volunteerService, EntityManagerInterface $entityManager, FichajeRepository $fichajeRepository): Response
    {
        $openFichaje = $fichajeRepository->findOpenFichaje($volunteerService);

        if ($openFichaje) {
            $this->addFlash('error', 'Este voluntario ya tiene un fichaje de entrada abierto.');
            return $this->redirectToRoute('app_service_edit', ['id' => $volunteerService->getService()->getId(), '_fragment' => 'asistencias']);
        }

        $fichaje = new Fichaje();
        $fichaje->setVolunteerService($volunteerService);
        $fichaje->setStartTime(new \DateTime());
        $fichaje->setNotes($request->request->get('notes'));

        $entityManager->persist($fichaje);
        $entityManager->flush();

        $this->addFlash('success', 'Entrada registrada correctamente.');

        return $this->redirectToRoute('app_service_edit', ['id' => $volunteerService->getService()->getId(), '_fragment' => 'asistencias']);
    }

    #[Route('/volunteer_service/{id}/clockout', name: 'app_fichaje_clockout', methods: ['POST'])]
    public function clockOut(Request $request, VolunteerService $volunteerService, EntityManagerInterface $entityManager, FichajeRepository $fichajeRepository): Response
    {
        $openFichaje = $fichajeRepository->findOpenFichaje($volunteerService);

        if (!$openFichaje) {
            $this->addFlash('error', 'No se encontró un fichaje de entrada abierto para este voluntario.');
            return $this->redirectToRoute('app_service_edit', ['id' => $volunteerService->getService()->getId(), '_fragment' => 'asistencias']);
        }

        $openFichaje->setEndTime(new \DateTime());
        $openFichaje->setNotes($request->request->get('notes'));

        if ($openFichaje->getEndTime() < $openFichaje->getStartTime()) {
            $this->addFlash('error', 'La hora de salida no puede ser anterior a la hora de entrada.');
            return $this->redirectToRoute('app_service_edit', ['id' => $volunteerService->getService()->getId(), '_fragment' => 'asistencias']);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Salida registrada correctamente.');

        return $this->redirectToRoute('app_service_edit', ['id' => $volunteerService->getService()->getId(), '_fragment' => 'asistencias']);
    }

    #[Route('/volunteer_service/{id}/fichajes', name: 'app_fichaje_get_for_volunteer', methods: ['GET'])]
    public function getFichajesForVolunteer(VolunteerService $volunteerService): Response
    {
        $fichajes = $volunteerService->getFichajes();
        $data = [];
        foreach ($fichajes as $fichaje) {
            $data[] = [
                'id' => $fichaje->getId(),
                'startTime' => $fichaje->getStartTime()->format('Y-m-d H:i:s'),
                'endTime' => $fichaje->getEndTime() ? $fichaje->getEndTime()->format('Y-m-d H:i:s') : null,
                'notes' => $fichaje->getNotes(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/volunteer_service/{id}/fichaje/add', name: 'app_fichaje_add_individual', methods: ['POST'])]
    public function addIndividualFichaje(Request $request, VolunteerService $volunteerService, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $startTime = new \DateTime($data['startTime']);
        $endTime = isset($data['endTime']) && $data['endTime'] ? new \DateTime($data['endTime']) : null;
        $notes = $data['notes'] ?? null;

        if ($endTime && $endTime < $startTime) {
            return $this->json(['success' => false, 'message' => 'La hora de fin no puede ser anterior a la hora de inicio.'], Response::HTTP_BAD_REQUEST);
        }

        $fichaje = new Fichaje();
        $fichaje->setVolunteerService($volunteerService);
        $fichaje->setStartTime($startTime);
        $fichaje->setEndTime($endTime);
        $fichaje->setNotes($notes);

        $entityManager->persist($fichaje);
        $entityManager->flush();

        return $this->json(['success' => true, 'message' => 'Fichaje individual guardado correctamente.']);
    }
}
