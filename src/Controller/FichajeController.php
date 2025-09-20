<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\VolunteerService;
use App\Repository\VolunteerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FichajeController extends AbstractController
{
    #[Route('/service/{id}/fichaje', name: 'app_fichaje', methods: ['POST'])]
    public function fichaje(Request $request, Service $service, EntityManagerInterface $entityManager, VolunteerRepository $volunteerRepository): Response
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

        foreach ($volunteerIds as $volunteerId) {
            $volunteer = $volunteerRepository->find($volunteerId);
            if ($volunteer) {
                $volunteerService = new VolunteerService();
                $volunteerService->setVolunteer($volunteer);
                $volunteerService->setService($service);
                $volunteerService->setStartTime($startTime);
                $volunteerService->setEndTime($endTime);
                $volunteerService->setDuration($durationInMinutes);
                $entityManager->persist($volunteerService);
            }
        }

        $entityManager->flush();

        $this->addFlash('success', 'Fichaje guardado correctamente.');

        return $this->redirectToRoute('app_service_edit', ['id' => $service->getId(), '_fragment' => 'asistencias']);
    }
}
