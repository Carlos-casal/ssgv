<?php
use App\Kernel;
use App\Entity\Material;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/vendor/autoload.php';

$env = 'dev';
$debug = true;
$kernel = new Kernel($env, $debug);
$kernel->boot();

$container = $kernel->getContainer();
$em = $container->get('doctrine')->getManager();
$repo = $em->getRepository(Material::class);

echo "Testing findOneBy with empty string for serialNumber:\n";
$mat = $repo->findOneBy(['serialNumber' => '']);
if ($mat) {
    echo "Found material with empty string sn: " . $mat->getName() . "\n";
} else {
    echo "Not found with empty string\n";
}

echo "Testing findOneBy with empty string for barcode:\n";
$mat = $repo->findOneBy(['barcode' => '']);
if ($mat) {
    echo "Found material with empty string barcode: " . $mat->getName() . "\n";
} else {
    echo "Not found with empty string\n";
}

// Let's dump all materials to see what's in the DB
$all = $repo->findAll();
echo "Total materials in DB: " . count($all) . "\n";
foreach ($all as $m) {
    echo "ID: " . $m->getId() . " | Name: " . $m->getName() . " | Barcode: " . $m->getBarcode() . " | SN: " . var_export($m->getSerialNumber(), true) . "\n";
}
