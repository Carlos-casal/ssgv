<?php

namespace App\Controller;

use App\Repository\MaterialRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/warehouse')]
class WarehouseController extends AbstractController
{
    #[Route('/', name: 'app_warehouse_dashboard')]
    public function index(MaterialRepository $materialRepository, VehicleRepository $vehicleRepository): Response
    {
        $materials = $materialRepository->findAll();
        $vehicles = $vehicleRepository->findAll();

        $stats = [
            'total_items' => count($materials),
            'low_stock' => count(array_filter($materials, fn($m) => $m->isLowStock())),
            'total_vehicles' => count($vehicles),
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
        ]);
    }
}
