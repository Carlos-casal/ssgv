<?php
use App\Kernel;
use App\Entity\Material;

require dirname(__DIR__).'/vendor/autoload.php';

(new \Symfony\Component\Dotenv\Dotenv())->bootEnv(dirname(__DIR__).'/.env');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

$container = $kernel->getContainer();
$em = $container->get('doctrine')->getManager();
$repo = $em->getRepository(Material::class);

echo "Total materials in DB: " . $repo->count([]) . "\n";
$all = $repo->findAll();
foreach ($all as $m) {
    echo "ID: " . $m->getId() . " | Name: " . $m->getName() . " | Barcode: " . $m->getBarcode() . " | SN: " . var_export($m->getSerialNumber(), true) . "\n";
}
