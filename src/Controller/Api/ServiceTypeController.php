<?php

namespace App\Controller\Api;

use App\Entity\ServiceType;
use App\Form\ServiceTypeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/service-type')]
class ServiceTypeController extends AbstractController
{
    #[Route('/new', name: 'api_service_type_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serviceType = new ServiceType();
        $form = $this->createForm(ServiceTypeType::class, $serviceType);

        $data = json_decode($request->getContent(), true);
        $form->submit($data['service_type'] ?? $data, false);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$serviceType->getCode()) {
                // Simple auto-coding: find max code and increment
                $last = $entityManager->getRepository(ServiceType::class)->findOneBy([], ['code' => 'DESC']);
                $newCode = $last ? (int)$last->getCode() + 1 : 1;
                $serviceType->setCode((string)$newCode);
            }

            $entityManager->persist($serviceType);
            $entityManager->flush();

            return $this->json([
                'id' => $serviceType->getId(),
                'name' => ($serviceType->getCode() ? $serviceType->getCode() . '. ' : '') . $serviceType->getName(),
            ], Response::HTTP_CREATED);
        }

        return $this->json([
            'errors' => (string) $form->getErrors(true, false),
        ], Response::HTTP_BAD_REQUEST);
    }
}
