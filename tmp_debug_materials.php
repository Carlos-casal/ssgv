<?php
require 'vendor/autoload.php';

use App\Kernel;
use App\Entity\Material;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');

$kernel = new Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
$kernel->boot();
$container = $kernel->getContainer();
$em = $container->get('doctrine.orm.entity_manager');

$name = 'Botiquín';
$materials = $em->getRepository(Material::class)->createQueryBuilder('m')
    ->where('m.name LIKE :name')
    ->setParameter('name', '%' . $name . '%')
    ->getQuery()
    ->getResult();

echo "Found " . count($materials) . " materials matching '$name':\n\n";

foreach ($materials as $m) {
    echo "ID: " . $m->getId() . "\n";
    echo "Name: " . $m->getName() . "\n";
    echo "Barcode: " . ($m->getBarcode() ?: 'NULL') . "\n";
    echo "Nature: " . $m->getNature() . "\n";
    echo "Category: " . $m->getCategory() . "\n";
    echo "Units Count: " . count($m->getUnits()) . "\n";
    foreach ($m->getUnits() as $u) {
        echo "  - Unit ID: " . $u->getId() . "\n";
        echo "    S/N: " . ($u->getSerialNumber() ?: 'N/A') . "\n";
        echo "    Alias: " . ($u->getAlias() ?: 'N/A') . "\n";
    }
    echo "--------------------------\n";
}
