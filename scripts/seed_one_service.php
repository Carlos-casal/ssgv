<?php

use App\Entity\Service;
use App\Entity\ServiceType;
use App\Entity\ServiceSubcategory;
use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

require dirname(__DIR__).'/vendor/autoload.php';

$kernel = new Kernel('dev', true);
$kernel->boot();

$em = $kernel->getContainer()->get('doctrine')->getManager();

$type = $em->getRepository(ServiceType::class)->findOneBy([]);
$sub = $em->getRepository(ServiceSubcategory::class)->findOneBy([]);

if (!$type || !$sub) {
    echo "No types/subcategories found. Run app:seed-services first.\n";
    exit(1);
}

$service = new Service();
$service->setTitle('Servicio de Prueba para Verificación');
$service->setStartDate(new \DateTime('+1 day'));
$service->setEndDate(new \DateTime('+1 day 4 hours'));
$service->setRegistrationLimitDate(new \DateTime('+1 day'));
$service->setTimeAtBase(new \DateTime('08:00'));
$service->setDepartureTime(new \DateTime('08:30'));
$service->setType($type);
$service->setSubcategory($sub);
$service->setLocality('Madrid');
$service->setAfluencia('baja');
$service->setArchived(false);

$em->persist($service);
$em->flush();

echo "Service seeded successfully.\n";
