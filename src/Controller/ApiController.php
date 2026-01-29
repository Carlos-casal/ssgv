<?php

namespace App\Controller;

use App\Entity\ServiceType;
use App\Entity\ServiceCategory;
use App\Entity\ServiceSubcategory;
use App\Entity\Material;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/categories/{typeId}', name: 'api_categories', methods: ['GET'])]
    public function getCategories(int $typeId, EntityManagerInterface $em): JsonResponse
    {
        $categories = $em->getRepository(ServiceCategory::class)->findBy(['serviceType' => $typeId]);
        $data = array_map(fn($c) => ['id' => $c->getId(), 'name' => $c->getName(), 'codigo' => $c->getCodigo()], $categories);
        return new JsonResponse($data);
    }

    #[Route('/subcategories/{categoryId}', name: 'api_subcategories', methods: ['GET'])]
    public function getSubcategories(int $categoryId, EntityManagerInterface $em): JsonResponse
    {
        $subcategories = $em->getRepository(ServiceSubcategory::class)->findBy(['serviceCategory' => $categoryId]);
        $data = array_map(fn($s) => ['id' => $s->getId(), 'name' => $s->getName(), 'codigo' => $s->getCodigo()], $subcategories);
        return new JsonResponse($data);
    }

    #[Route('/materials/new', name: 'api_material_new', methods: ['POST'])]
    public function newMaterial(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $material = new Material();
        $material->setName($data['name']);
        $material->setCategory($data['category']);
        $em->persist($material);
        $em->flush();

        return new JsonResponse(['id' => $material->getId(), 'name' => $material->getName()]);
    }

    #[Route('/materials/{category}', name: 'api_materials', methods: ['GET'])]
    public function getMaterials(string $category, EntityManagerInterface $em): JsonResponse
    {
        $materials = $em->getRepository(Material::class)->findBy(['category' => $category]);
        $data = array_map(fn($m) => ['id' => $m->getId(), 'name' => $m->getName()], $materials);
        return new JsonResponse($data);
    }
}
