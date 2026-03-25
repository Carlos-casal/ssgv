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

    #[Route('/kits/list', name: 'api_kits_list', methods: ['GET'])]
    public function kitsList(EntityManagerInterface $entityManager): Response
    {
        $kits = $entityManager->getRepository(\App\Entity\MaterialUnit::class)->createQueryBuilder('u')
            ->leftJoin('u.material', 'm')
            ->leftJoin('u.template', 't')
            ->where('u.template IS NOT NULL')
            ->andWhere('u.operationalStatus = :status')
            ->setParameter('status', 'OPERATIVO')
            ->getQuery()
            ->getResult();

        $data = [];
        foreach ($kits as $kit) {
            $data[] = [
                'id' => $kit->getId(),
                'alias' => $kit->getAlias(),
                'serialNumber' => $kit->getSerialNumber(),
                'materialId' => $kit->getMaterial()->getId(),
                'materialName' => $kit->getMaterial()->getName(),
                'templateName' => $kit->getTemplate()->getName()
            ];
        }
        return $this->json($data);
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
    public function checkAvailability(Request $request, MaterialManager $materialManager, MaterialRepository $materialRepository, EntityManagerInterface $entityManager): Response
    {
        $id = $request->query->get('id');
        $startStr = $request->query->get('start');
        $endStr = $request->query->get('end');
        $quantity = (int)$request->query->get('quantity', 1);
        $excludeServiceId = $request->query->get('excludeServiceId') ? (int)$request->query->get('excludeServiceId') : null;

        if (!$id || !$startStr || !$endStr) {
            return $this->json([
                'available' => true,
                'totalAvailable' => 0,
                'suggestedUnits' => [],
                'nature' => 'UNKNOWN',
                'message' => 'Parámetros insuficientes para validación'
            ]);
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
                'suggestedUnits' => [],
                'nature' => 'CONSUMIBLE',
                'message' => $available ? 'OK' : 'Stock insuficiente (Disponibles: ' . $material->getStock() . ')'
            ]);
        } else {
            // Requirement: "en el segundo despegable aparecerá todos los que están operativos incluyendo los que están en otro servicio pero este en rojo"
            // We fetch ALL operational units and mark their availability individually.
            // Requirement: "equipo técnico que no esté dentro de botiquines"
            // We must filter out units that are currently located inside a KIT.
            $qb = $entityManager->getRepository(\App\Entity\MaterialUnit::class)->createQueryBuilder('u')
                ->leftJoin('u.location', 'l')
                ->where('u.material = :material')
                ->andWhere('u.operationalStatus = :status')
                ->andWhere('l.type != :kitType OR l.type IS NULL')
                ->setParameter('material', $material)
                ->setParameter('status', 'OPERATIVO')
                ->setParameter('kitType', \App\Entity\Location::TYPE_KIT);

            $allUnits = $qb->getQuery()->getResult();

            $suggestedData = [];
            $totalAvailable = 0;

            foreach ($allUnits as $unit) {
                $unitAvailable = $materialManager->isUnitAvailable($unit, $start, $end, $excludeServiceId);

                $reason = 'OK';
                if ($unit->isInMaintenance()) {
                    $reason = 'MANTENIMIENTO';
                    $unitAvailable = false; // Maintenance overrides calculation
                } elseif (!$unitAvailable) {
                    $reason = 'EN OTRO SERVICIO';
                }

                if ($unitAvailable) {
                    $totalAvailable++;
                }

                $suggestedData[] = [
                    'id' => $unit->getId(),
                    'serialNumber' => $unit->getSerialNumber(),
                    'collectiveNumber' => $unit->getCollectiveNumber(),
                    'alias' => $unit->getAlias(),
                    'available' => (bool)$unitAvailable,
                    'reason' => $reason
                ];
            }

            // Global availability for the requested quantity
            $available = $totalAvailable >= $quantity;

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
