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

        // Decode the JSON request body
        $data = json_decode($request->getContent(), true);

        // The second parameter `false` tells the form to not clear missing fields,
        // which is important for PATCH-like updates and APIs.
        $form->submit($data['service_type'] ?? $data, false);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($serviceType);
            $entityManager->flush();

            return $this->json([
                'id' => $serviceType->getId(),
                'name' => $serviceType->getName(),
            ], Response::HTTP_CREATED);
        }

        return $this->json([
            'errors' => (string) $form->getErrors(true, false),
        ], Response::HTTP_BAD_REQUEST);
    }
}
