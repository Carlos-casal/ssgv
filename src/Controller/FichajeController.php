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

    #[Route('/volunteer_service/{id}/add_fichaje', name: 'app_fichaje_add_individual', methods: ['POST'])]
    public function addIndividualFichaje(Request $request, VolunteerService $volunteerService, EntityManagerInterface $entityManager, FichajeRepository $fichajeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_COORDINATOR');

        $startDateStr = $request->request->get('start_date');
        $startTimeStr = $request->request->get('start_time');
        $endDateStr = $request->request->get('end_date');
        $endTimeStr = $request->request->get('end_time');
        $notes = $request->request->get('notes');

        $isAjax = $request->headers->get('X-Requested-With') === 'XMLHttpRequest';

        if (empty($startDateStr) || empty($startTimeStr)) {
            if ($isAjax) {
                return $this->json(['success' => false, 'message' => 'La fecha y hora de inicio son obligatorias.'], Response::HTTP_BAD_REQUEST);
            }
            $this->addFlash('error', 'La fecha y hora de inicio son obligatorias.');
            return $this->redirectToRoute('app_service_edit', ['id' => $volunteerService->getService()->getId(), '_fragment' => 'asistencias']);
        }

        try {
            $startTime = new \DateTime("$startDateStr $startTimeStr");

            $lastFichaje = $fichajeRepository->findOneBy(['volunteerService' => $volunteerService], ['endTime' => 'DESC']);
            if ($lastFichaje && $lastFichaje->getEndTime() && $startTime < $lastFichaje->getEndTime()) {
                $errorMessage = 'La nueva hora de entrada no puede ser anterior a la última hora de salida registrada: ' . $lastFichaje->getEndTime()->format('d/m/Y H:i');
                if ($isAjax) {
                    return $this->json(['success' => false, 'message' => $errorMessage], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
                $this->addFlash('error', $errorMessage);
                return $this->redirectToRoute('app_service_edit', ['id' => $volunteerService->getService()->getId(), '_fragment' => 'asistencias']);
            }

            $endTime = null;
            if (!empty($endDateStr) && !empty($endTimeStr)) {
                $endTime = new \DateTime("$endDateStr $endTimeStr");
                if ($endTime < $startTime) {
                    $errorMessage = 'La hora de fin no puede ser anterior a la hora de inicio.';
                    if ($isAjax) {
                        return $this->json(['success' => false, 'message' => $errorMessage], Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                    $this->addFlash('error', $errorMessage);
                    return $this->redirectToRoute('app_service_edit', ['id' => $volunteerService->getService()->getId(), '_fragment' => 'asistencias']);
                }
            }

            $fichaje = new Fichaje();
            $fichaje->setVolunteerService($volunteerService);
            $fichaje->setStartTime($startTime);
            $fichaje->setEndTime($endTime);
            $fichaje->setNotes($notes);

            $entityManager->persist($fichaje);
            $entityManager->flush();

            if ($isAjax) {
                // After saving, find the most recent clock-out time again to return it
                $newLastFichaje = $fichajeRepository->findOneBy(['volunteerService' => $volunteerService], ['endTime' => 'DESC']);
                $lastClockOut = $newLastFichaje ? $newLastFichaje->getEndTime() : null;

                return $this->json([
                    'success' => true,
                    'message' => 'Fichaje añadido correctamente.',
                    'lastClockOut' => $lastClockOut ? $lastClockOut->format('Y-m-d H:i:s') : null
                ]);
            }

            $this->addFlash('success', 'Fichaje añadido correctamente.');
            return $this->redirectToRoute('app_service_edit', ['id' => $volunteerService->getService()->getId(), '_fragment' => 'asistencias']);

        } catch (\Exception $e) {
            if ($isAjax) {
                return $this->json(['success' => false, 'message' => 'Error en el formato de fecha/hora.'], Response::HTTP_BAD_REQUEST);
            }
            $this->addFlash('error', 'Error en el formato de fecha/hora.');
            return $this->redirectToRoute('app_service_edit', ['id' => $volunteerService->getService()->getId(), '_fragment' => 'asistencias']);
        }
    }

    #[Route('/volunteer_service/{id}/clockin', name: 'app_fichaje_clockin', methods: ['POST'])]
    public function clockIn(Request $request, VolunteerService $volunteerService, EntityManagerInterface $entityManager, FichajeRepository $fichajeRepository): Response
    {
        // 1. Check if there is already an open clock-in
        $openFichaje = $fichajeRepository->findOpenFichaje($volunteerService);
        if ($openFichaje) {
            $this->addFlash('error', 'Este voluntario ya tiene un fichaje de entrada abierto.');
            return $this->redirectToRoute('app_service_edit', ['id' => $volunteerService->getService()->getId(), '_fragment' => 'asistencias']);
        }

        // 2. Overlap validation: check the new start time against the last end time
        $lastFichaje = $fichajeRepository->findOneBy(['volunteerService' => $volunteerService], ['endTime' => 'DESC']);
        $newStartTime = new \DateTime();

        if ($lastFichaje && $lastFichaje->getEndTime() && $newStartTime < $lastFichaje->getEndTime()) {
            $this->addFlash('error', 'La hora de entrada no puede ser anterior a la última hora de salida registrada: ' . $lastFichaje->getEndTime()->format('H:i:s'));
            return $this->redirectToRoute('app_service_edit', ['id' => $volunteerService->getService()->getId(), '_fragment' => 'asistencias']);
        }

        // 3. Create the new clock-in record
        $fichaje = new Fichaje();
        $fichaje->setVolunteerService($volunteerService);
        $fichaje->setStartTime($newStartTime);
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

    #[Route('/fichaje/{id}/delete', name: 'app_fichaje_delete', methods: ['POST'])]
    public function deleteFichaje(Request $request, Fichaje $fichaje, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_COORDINATOR');

        $serviceId = $fichaje->getVolunteerService()->getService()->getId();

        if ($this->isCsrfTokenValid('delete'.$fichaje->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fichaje);
            $entityManager->flush();
            $this->addFlash('success', 'El registro de fichaje ha sido eliminado.');
        } else {
            $this->addFlash('error', 'Token CSRF inválido.');
        }

        return $this->redirectToRoute('app_service_edit', ['id' => $serviceId, '_fragment' => 'asistencias']);
    }

    #[Route('/fichaje/{id}/edit', name: 'app_fichaje_edit', methods: ['POST'])]
    public function editFichaje(Request $request, Fichaje $fichaje, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_COORDINATOR');

        $serviceId = $fichaje->getVolunteerService()->getService()->getId();

        $startDateStr = $request->request->get('start_date');
        $startTimeStr = $request->request->get('start_time');
        $endDateStr = $request->request->get('end_date');
        $endTimeStr = $request->request->get('end_time');
        $notes = $request->request->get('notes');

        if (empty($startDateStr) || empty($startTimeStr)) {
            $this->addFlash('error', 'La fecha y hora de inicio son obligatorias.');
            return $this->redirectToRoute('app_service_edit', ['id' => $serviceId, '_fragment' => 'asistencias']);
        }

        $startTime = new \DateTime("$startDateStr $startTimeStr");
        $endTime = null;
        if (!empty($endDateStr) && !empty($endTimeStr)) {
            $endTime = new \DateTime("$endDateStr $endTimeStr");

            if ($endTime < $startTime) {
                $this->addFlash('error', 'La hora de fin no puede ser anterior a la hora de inicio.');
                return $this->redirectToRoute('app_service_edit', ['id' => $serviceId, '_fragment' => 'asistencias']);
            }
        }

        $fichaje->setStartTime($startTime);
        $fichaje->setEndTime($endTime);
        $fichaje->setNotes($notes);

        $entityManager->flush();

        $this->addFlash('success', 'Fichaje actualizado correctamente.');

        return $this->redirectToRoute('app_service_edit', ['id' => $serviceId, '_fragment' => 'asistencias']);
    }
}
