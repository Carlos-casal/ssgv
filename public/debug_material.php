<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use App\Entity\Material;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

$em = $kernel->getContainer()->get('doctrine')->getManager();
$material = $em->getRepository(Material::class)->find(32);

if (!$material) {
    echo "Material 32 not found.\n";
    exit;
}

echo "Material ID: " . $material->getId() . "\n";
echo "Name: " . $material->getName() . "\n";
echo "Nature: " . $material->getNature() . "\n";
echo "Total Stock: " . $material->getStock() . "\n";

$units = $material->getUnits();
echo "Units count: " . count($units) . "\n";
foreach ($units as $index => $unit) {
    echo sprintf(
        "  Unit %d: ID=%d, Alias=%s, SN=%s, BrandModel=%s\n",
        $index + 1,
        $unit->getId(),
        $unit->getAlias() ?? 'null',
        $unit->getSerialNumber() ?? 'null',
        $unit->getBrandModel() ?? 'null'
    );
}

$batches = $material->getBatches();
echo "Batches count: " . count($batches) . "\n";
foreach ($batches as $index => $batch) {
    echo sprintf(
        "  Batch %d: ID=%d, Number=%s, Stock=%d\n",
        $index + 1,
        $batch->getId(),
        $batch->getBatchNumber(),
        $batch->getUnitsPerPackage() * $batch->getNumPackages()
    );
}
