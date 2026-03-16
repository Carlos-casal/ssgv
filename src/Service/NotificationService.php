<?php

namespace App\Service;

use App\Repository\MaterialRepository;
use App\Entity\Material;

class NotificationService
{
    private MaterialRepository $materialRepository;

    public function __construct(MaterialRepository $materialRepository)
    {
        $this->materialRepository = $materialRepository;
    }

    public function getAlerts(): array
    {
        $alerts = [];
        $materials = $this->materialRepository->findAll();

        foreach ($materials as $material) {
            // Low stock check
            if ($material->isLowStock()) {
                $alerts[] = [
                    'type' => 'low_stock',
                    'title' => 'Stock Bajo',
                    'message' => 'El material "' . $material->getName() . '" está por debajo del stock mínimo.',
                    'link' => '/material/' . $material->getId(),
                    'severity' => 'warning'
                ];
            }

            // Expiry check
            $status = $material->getExpirationStatus();
            if ($status === 'red') {
                $alerts[] = [
                    'type' => 'expired',
                    'title' => 'Material Caducado',
                    'message' => 'El material "' . $material->getName() . '" ha caducado.',
                    'link' => '/material/' . $material->getId(),
                    'severity' => 'danger'
                ];
            } elseif ($status === 'orange') {
                $alerts[] = [
                    'type' => 'expiring_soon',
                    'title' => 'Próxima Caducidad',
                    'message' => 'El material "' . $material->getName() . '" caducará pronto.',
                    'link' => '/material/' . $material->getId(),
                    'severity' => 'warning'
                ];
            }
        }

        return $alerts;
    }
}
