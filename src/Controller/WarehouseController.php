<?php

namespace App\Controller;

use App\Repository\LocationReviewRepository;
use App\Repository\MaterialRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/warehouse')]
class WarehouseController extends AbstractController
{
    #[Route('/', name: 'app_warehouse_dashboard')]
    public function index(
        MaterialRepository $materialRepository,
        VehicleRepository $vehicleRepository,
        LocationReviewRepository $reviewRepository,
        \App\Repository\LocationRepository $locationRepository
    ): Response {
        $materials = $materialRepository->findBy([], ['id' => 'DESC']);
        $vehicles = $vehicleRepository->findAll();
        // Filter out Almacén Central and orphaned/deleted KIT locations
        $locations = $locationRepository->createQueryBuilder('l')
            ->leftJoin('l.materialUnit', 'mu')
            ->where('l.name != :almacenCentral')
            ->andWhere('l.type != :kitType OR mu.id IS NOT NULL')
            ->setParameter('almacenCentral', 'Almacén Central')
            ->setParameter('kitType', \App\Entity\Location::TYPE_KIT)
            ->getQuery()
            ->getResult();
            
        $recentReviews = $reviewRepository->findBy([], ['reviewDate' => 'DESC'], 5);

        $totalValuation = 0;
        foreach ($materials as $material) {
            if ($material->getNature() === \App\Entity\Material::NATURE_CONSUMABLE) {
                if (!$material->getBatches()->isEmpty()) {
                    foreach ($material->getBatches() as $batch) {
                        $batchStock = 0;
                        foreach ($batch->getStocks() as $s) {
                            $batchStock += $s->getQuantity();
                        }

                        $uPrice = str_replace(',', '.', (string)$batch->getUnitPrice());
                        $totalValuation += (float)$uPrice * $batchStock;
                    }
                } else {
                    $uPrice = str_replace(',', '.', (string)$material->getUnitPrice());
                    $totalValuation += (float)$uPrice * $material->getStock();
                }
            } else {
                // For Technical/Equipment: Sum individual unit valuations
                $units = $material->getUnits();
                if (!$units->isEmpty()) {
                    foreach ($units as $unit) {
                        $totalValuation += $unit->getValuation();
                    }
                } else {
                    // Fallback if no units exist yet
                    $uPrice = str_replace(',', '.', (string)$material->getUnitPrice());
                    $totalValuation += (float)$uPrice * $material->getStock();
                }
            }
        }

        $stats = [
            'total_items' => count($materials),
            'low_stock' => count(array_filter($materials, fn($m) => $m->isLowStock())),
            'total_vehicles' => count($vehicles),
            'total_valuation' => $totalValuation,
        ];

        // Group materials by category for the cards
        $categories = [
            'Sanitario' => ['count' => 0, 'low_stock' => 0, 'icon' => 'cross', 'color' => 'red'],
            'Comunicaciones' => ['count' => 0, 'low_stock' => 0, 'icon' => 'radio', 'color' => 'blue'],
            'Logística' => ['count' => 0, 'low_stock' => 0, 'icon' => 'truck', 'color' => 'amber'],
            'Mar' => ['count' => 0, 'low_stock' => 0, 'icon' => 'anchor', 'color' => 'cyan'],
            'Uniformidad' => ['count' => 0, 'low_stock' => 0, 'icon' => 'shirt', 'color' => 'indigo'],
            'Varios' => ['count' => 0, 'low_stock' => 0, 'icon' => 'more-horizontal', 'color' => 'slate'],
        ];

        foreach ($materials as $material) {
            if (isset($categories[$material->getCategory()])) {
                $categories[$material->getCategory()]['count']++;
                if ($material->isLowStock()) {
                    $categories[$material->getCategory()]['low_stock']++;
                }
            }
        }

        return $this->render('warehouse/index.html.twig', [
            'stats' => $stats,
            'categories' => $categories,
            'vehicle_count' => count($vehicles),
            'materials' => $materials,
            'recent_reviews' => $recentReviews,
            'locations' => $locations,
        ]);
    }
}
