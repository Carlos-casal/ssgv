<?php

namespace App\Controller\Api;

use App\Entity\Material;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/material')]
class MaterialController extends AbstractController
{
    #[Route('/new', name: 'api_material_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? null;
        $category = $data['category'] ?? null;

        if (!$name) {
            return $this->json(['error' => 'Nombre es requerido'], Response::HTTP_BAD_REQUEST);
        }

        $material = new Material();
        $material->setName($name);
        $material->setCategory($category);

        $entityManager->persist($material);
        $entityManager->flush();

        return $this->json([
            'id' => $material->getId(),
            'name' => $material->getName(),
            'category' => $material->getCategory(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/list', name: 'api_material_list', methods: ['GET'])]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $materials = $entityManager->getRepository(Material::class)->findAll();
        $data = [];
        foreach ($materials as $material) {
            $data[] = [
                'id' => $material->getId(),
                'name' => $material->getName(),
                'category' => $material->getCategory(),
            ];
        }
        return $this->json($data);
    }
}
