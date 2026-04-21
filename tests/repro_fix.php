<?php

use App\Entity\Location;
use App\Entity\Material;
use App\Entity\MaterialStock;
use App\Service\MaterialManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

require_once __DIR__ . '/vendor/autoload.php';

// This is a mockup since we cannot easily run a full KernelTestCase without a proper environment
// But I can write the logic that I would use to verify.

class StockTransferTest
{
    public function testTransferDoesNotDuplicate(MaterialManager $materialManager, EntityManagerInterface $em)
    {
        // 1. Setup
        $material = new Material();
        $material->setName('Test Material');
        $material->setNature(Material::NATURE_CONSUMABLE);
        $material->setStock(10);
        $em->persist($material);

        $origin = new Location();
        $origin->setName('Origin Kit');
        $origin->setType(Location::TYPE_KIT);
        $em->persist($origin);

        $destination = new Location();
        $destination->setName('Destination Kit');
        $destination->setType(Location::TYPE_KIT);
        $em->persist($destination);

        // Initial stock in origin
        $stock = new MaterialStock();
        $stock->setMaterial($material);
        $stock->setLocation($origin);
        $stock->setQuantity(10);
        $em->persist($stock);

        $em->flush();

        echo "Initial state: Origin has " . $stock->getQuantity() . " units.\n";

        // 2. Perform transfer
        $materialManager->transfer(
            $material,
            $origin,
            $destination,
            5,
            'Test Transfer',
            null
        );

        $em->flush();

        // 3. Verify
        $originStocks = $em->getRepository(MaterialStock::class)->findBy(['material' => $material, 'location' => $origin]);
        $destStocks = $em->getRepository(MaterialStock::class)->findBy(['material' => $material, 'location' => $destination]);

        echo "After transfer:\n";
        echo "Origin stocks: " . count($originStocks) . " (Qty: " . ($originStocks[0]->getQuantity() ?? 0) . ")\n";
        echo "Destination stocks: " . count($destStocks) . " (Qty: " . ($destStocks[0]->getQuantity() ?? 0) . ")\n";

        if (count($destStocks) > 1) {
            throw new \Exception("DUPLICATE STOCK DETECTED IN DESTINATION!");
        }

        // 4. Perform another transfer of the remaining
        $materialManager->transfer(
            $material,
            $origin,
            $destination,
            5,
            'Test Transfer 2',
            null
        );

        $em->flush();

        $originStocksAfter = $em->getRepository(MaterialStock::class)->findBy(['material' => $material, 'location' => $origin]);
        echo "After second transfer:\n";
        echo "Origin stocks remaining: " . count($originStocksAfter) . "\n";

        if (count($originStocksAfter) > 0 && $originStocksAfter[0]->getQuantity() == 0) {
            echo "Warning: Stock with 0 quantity still exists in origin (expected to be removed if non-warehouse)\n";
        }
    }
}
