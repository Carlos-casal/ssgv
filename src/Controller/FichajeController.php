<?php

namespace App\Controller;

use App\Entity\Fichaje;
use App\Entity\Service;
use App\Entity\Volunteer;
use App\Repository\AssistanceConfirmationRepository;
use App\Repository\FichajeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FichajeController extends AbstractController
{
    #[Route('/fichaje/todos/{id}', name: 'app_fichaje_todos', methods: ['POST'])]
    public function ficharTodos(Request $request, Service $service, EntityManagerInterface $entityManager, AssistanceConfirmationRepository $assistanceConfirmationRepository): Response
    {
        $data = $request->request->all();
        $volunteerIds = $data['volunteers'] ?? [];
        $date = $data['date'] ?? null;
        $time = $data['time'] ?? null;
        $note = $data['note'] ?? null;

        if (!$date || !$time) {
            $this->addFlash('error', 'La fecha y la hora son obligatorias.');
            return $this->redirectToRoute('app_service_edit', ['id' => $service->getId(), '_fragment' => 'asistencias']);
        }

        $timestamp = new \DateTime($date . ' ' . $time);

        foreach ($volunteerIds as $volunteerId) {
            $confirmation = $assistanceConfirmationRepository->findOneBy(['service' => $service, 'volunteer' => $volunteerId]);
            if ($confirmation && $confirmation->getStatus() === 'attending') {
                // Remove existing fichajes for this user and service
                foreach ($confirmation->getFichajes() as $fichaje) {
                    $entityManager->remove($fichaje);
                }

                $fichaje = new Fichaje();
                $fichaje->setAssistanceConfirmation($confirmation);
                $fichaje->setType('in'); // Or some other default type for mass fichaje
                $fichaje->setTimestamp($timestamp);
                $fichaje->setNote($note);
                $entityManager->persist($fichaje);
            }
        }

        $entityManager->flush();

        $this->addFlash('success', 'Fichaje para todos guardado correctamente.');

        return $this->redirectToRoute('app_service_edit', ['id' => $service->getId(), '_fragment' => 'asistencias']);
    }

    #[Route('/fichaje/{serviceId}/{volunteerId}', name: 'app_fichaje_individual', methods: ['POST'])]
    public function ficharIndividual(Request $request, int $serviceId, int $volunteerId, EntityManagerInterface $entityManager, AssistanceConfirmationRepository $assistanceConfirmationRepository): JsonResponse
    {
        $data = $request->request->all();
        $date = $data['date'] ?? null;
        $time = $data['time'] ?? null;
        $type = $data['type'] ?? null;
        $note = $data['note'] ?? null;

        if (!$date || !$time || !$type) {
            return new JsonResponse(['success' => false, 'message' => 'Fecha, hora y tipo son obligatorios.'], Response::HTTP_BAD_REQUEST);
        }

        $confirmation = $assistanceConfirmationRepository->findOneBy(['service' => $serviceId, 'volunteer' => $volunteerId]);

        if (!$confirmation) {
            return new JsonResponse(['success' => false, 'message' => 'No se encontró la confirmación de asistencia.'], Response::HTTP_NOT_FOUND);
        }

        $timestamp = new \DateTime($date . ' ' . $time);

        $fichaje = new Fichaje();
        $fichaje->setAssistanceConfirmation($confirmation);
        $fichaje->setType($type);
        $fichaje->setTimestamp($timestamp);
        $fichaje->setNote($note);

        $entityManager->persist($fichaje);
        $entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Fichaje guardado correctamente.']);
    }
}
