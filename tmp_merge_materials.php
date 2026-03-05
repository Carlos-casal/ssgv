<?php
// Script para fusionar registros duplicados de "Botiquín"
// Mueve la unidad del material duplicado (ID 24) al material original (ID 23) y elimina el duplicado.

require 'vendor/autoload.php';

use App\Kernel;
use App\Entity\Material;
use App\Entity\MaterialUnit;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');

$kernel = new Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
$kernel->boot();
$container = $kernel->getContainer();
$em = $container->get('doctrine.orm.entity_manager');

// --- Configuration: Adjust these IDs if needed ---
$keepId    = 23; // Material to KEEP (master record)
$mergeId   = 24; // Material to MERGE & DELETE (duplicate)

$masterMaterial = $em->find(Material::class, $keepId);
$duplicateMaterial = $em->find(Material::class, $mergeId);

if (!$masterMaterial || !$duplicateMaterial) {
    echo "ERROR: Could not find one or both materials.\n";
    exit(1);
}

echo "Master material (ID $keepId): " . $masterMaterial->getName() . " - " . count($masterMaterial->getUnits()) . " units\n";
echo "Duplicate material (ID $mergeId): " . $duplicateMaterial->getName() . " - " . count($duplicateMaterial->getUnits()) . " units\n\n";

$movedCount = 0;
foreach ($duplicateMaterial->getUnits() as $unit) {
    echo "  Moving Unit ID " . $unit->getId() . " (S/N: " . ($unit->getSerialNumber() ?: 'N/A') . ") to master material...\n";
    $unit->setMaterial($masterMaterial);
    $movedCount++;
}

$em->flush();
$em->remove($duplicateMaterial);
$em->flush();

echo "\nDone! Moved $movedCount unit(s). Deleted duplicate material ID $mergeId.\n";
echo "Master material now has " . count($masterMaterial->getUnits()) . " units.\n";
