<?php

namespace App\Controller\Api;

use App\Entity\Material;
use App\Repository\MaterialRepository;
use App\Service\MaterialManager;
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
        $nature = $data['nature'] ?? Material::NATURE_CONSUMABLE;

        if (!$name) {
            return $this->json(['error' => 'Nombre es requerido'], Response::HTTP_BAD_REQUEST);
        }

        $material = new Material();
        $material->setName($name);
        $material->setCategory($category);
        $material->setNature($nature);

        $entityManager->persist($material);
        $entityManager->flush();

        return $this->json([
            'id' => $material->getId(),
            'name' => $material->getName(),
            'category' => $material->getCategory(),
            'nature' => $material->getNature()
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
                'nature' => $material->getNature()
            ];
        }
        return $this->json($data);
    }

    #[Route('/check-availability', name: 'api_material_check_availability', methods: ['GET'])]
    public function checkAvailability(Request $request, MaterialManager $materialManager, MaterialRepository $materialRepository): Response
    {
        $id = $request->query->get('id');
        $startStr = $request->query->get('start');
        $endStr = $request->query->get('end');
        $quantity = (int)$request->query->get('quantity', 1);
        $excludeServiceId = $request->query->get('excludeServiceId') ? (int)$request->query->get('excludeServiceId') : null;

        if (!$id || !$startStr || !$endStr) {
            return $this->json(['error' => 'Missing parameters'], Response::HTTP_BAD_REQUEST);
        }

        $material = $materialRepository->find($id);
        if (!$material) {
            return $this->json(['error' => 'Material not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $start = new \DateTime($startStr);
            $end = new \DateTime($endStr);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Invalid dates'], Response::HTTP_BAD_REQUEST);
        }

        if ($material->getNature() === Material::NATURE_CONSUMABLE) {
            $available = $materialManager->hasEnoughStock($material, $quantity);
            return $this->json([
                'available' => $available,
                'stock' => $material->getStock(),
                'totalAvailable' => $material->getStock(),
                'nature' => 'CONSUMIBLE',
                'message' => $available ? 'OK' : 'Stock insuficiente (Disponibles: ' . $material->getStock() . ')'
            ]);
        } else {
            $allAvailable = $materialManager->suggestUnits($material, $start, $end, null, $excludeServiceId);
            $totalAvailable = count($allAvailable);

            // Re-run with quantity limit for suggestions if needed, or just take first N
            $suggested = array_slice($allAvailable, 0, $quantity);
            $available = count($allAvailable) >= $quantity;

            $suggestedData = [];
            foreach ($suggested as $unit) {
                $suggestedData[] = [
                    'id' => $unit->getId(),
                    'serialNumber' => $unit->getSerialNumber()
                ];
            }

            return $this->json([
                'available' => $available,
                'totalAvailable' => $totalAvailable,
                'suggestedUnits' => $suggestedData,
                'nature' => 'EQUIPO_TECNICO',
                'message' => $available ? 'OK' : 'Solo hay ' . $totalAvailable . ' unidades disponibles para estas fechas'
            ]);
        }
    }
}
