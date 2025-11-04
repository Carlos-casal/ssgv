<?php

namespace App\Controller\Api;

use App\Entity\ServiceCategory;
use App\Form\ServiceCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/service-category')]
class ServiceCategoryController extends AbstractController
{
    #[Route('/new', name: 'api_service_category_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serviceCategory = new ServiceCategory();
        $form = $this->createForm(ServiceCategoryType::class, $serviceCategory);

        // Decode the JSON request body
        $data = json_decode($request->getContent(), true);

        // The second parameter `false` tells the form to not clear missing fields,
        // which is important for PATCH-like updates and APIs.
        $form->submit($data['service_category'] ?? $data, false);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($serviceCategory);
            $entityManager->flush();

            return $this->json([
                'id' => $serviceCategory->getId(),
                'name' => $serviceCategory->getName(),
            ], Response::HTTP_CREATED);
        }

        return $this->json([
            'errors' => (string) $form->getErrors(true, false),
        ], Response::HTTP_BAD_REQUEST);
    }
}
