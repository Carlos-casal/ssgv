<?php
namespace App\Controller;

use App\Entity\Material;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DebugController extends AbstractController
{
    #[Route('/debug/material/{id}', name: 'app_debug_material')]
    public function debugMaterial(int $id, EntityManagerInterface $em): Response
    {
        $material = $em->getRepository(Material::class)->find($id);

        if (!$material) {
            return new Response("Material not found.");
        }

        $output = "Material ID: " . $material->getId() . "\n";
        $output .= "Name: " . $material->getName() . "\n";
        $output .= "Nature: " . $material->getNature() . "\n";
        $output .= "Total Stock: " . $material->getStock() . "\n";

        $units = $material->getUnits();
        $output .= "Units count: " . count($units) . "\n";
        foreach ($units as $index => $unit) {
            $output .= sprintf(
                "  Unit %d: ID=%d, Alias=%s, SN=%s, BrandModel=%s\n",
                $index + 1,
                $unit->getId(),
                $unit->getAlias() ?? 'null',
                $unit->getSerialNumber() ?? 'null',
                $unit->getBrandModel() ?? 'null'
            );
        }

        $batches = $material->getBatches();
        $output .= "Batches count: " . count($batches) . "\n";
        foreach ($batches as $index => $batch) {
            $output .= sprintf(
                "  Batch %d: ID=%d, Number=%s, Stock=%d\n",
                $index + 1,
                $batch->getId(),
                $batch->getBatchNumber(),
                $batch->getUnitsPerPackage() * $batch->getNumPackages()
            );
        }

        return new Response("<pre>" . htmlspecialchars($output) . "</pre>");
    }
}
