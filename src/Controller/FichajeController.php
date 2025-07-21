<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\AssistanceConfirmationRepository;
use App\Repository\VolunteerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FichajeController extends AbstractController
{
    #[Route('/service/{id}/fichaje', name: 'app_fichaje', methods: ['POST'])]
    public function fichaje(Request $request, Service $service, EntityManagerInterface $entityManager, VolunteerRepository $volunteerRepository, AssistanceConfirmationRepository $assistanceConfirmationRepository): Response
    {
        $data = $request->request->all();
        $startTime = new \DateTime($data['start-time']);
        $endTime = new \DateTime($data['end-time']);
        $volunteerIds = $data['volunteers'] ?? [];

        foreach ($volunteerIds as $volunteerId) {
            $volunteer = $volunteerRepository->find($volunteerId);
            if ($volunteer) {
                $assistanceConfirmation = $assistanceConfirmationRepository->findOneBy(['volunteer' => $volunteer, 'service' => $service]);
                if ($assistanceConfirmation) {
                    $assistanceConfirmation->setCheckIn($startTime);
                    $assistanceConfirmation->setCheckOut($endTime);
                }
            }
        }

        $entityManager->flush();

        $this->addFlash('success', 'Fichaje guardado correctamente.');

        return $this->redirectToRoute('app_service_attendance', ['id' => $service->getId()]);
    }
}
