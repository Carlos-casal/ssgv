<?php

namespace App\Command;

use App\Entity\ServiceType;
use App\Entity\ServiceCategory;
use App\Entity\ServiceSubcategory;
use App\Entity\Material;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-services',
    description: 'Seeds the service hierarchy and materials.',
)]
class SeedServicesCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // 1. Seed Service Types
        $types = [
            ['code' => '1', 'name' => 'Preventivos'],
            ['code' => '2', 'name' => 'Emergencias'],
            ['code' => '3', 'name' => 'Formación'],
            ['code' => '4', 'name' => 'Otros'],
        ];

        $typeEntities = [];
        foreach ($types as $t) {
            $type = $this->entityManager->getRepository(ServiceType::class)->findOneBy(['code' => $t['code']]);
            if (!$type) {
                $type = new ServiceType();
                $type->setCode($t['code']);
                $type->setName($t['name']);
                $this->entityManager->persist($type);
            }
            $typeEntities[$t['code']] = $type;
        }

        // 2. Seed Service Categories
        $categories = [
            ['code' => '1.1', 'name' => 'Deportivos', 'type' => '1'],
            ['code' => '1.2', 'name' => 'Culturales', 'type' => '1'],
            ['code' => '2.1', 'name' => 'Incendios', 'type' => '2'],
            ['code' => '2.2', 'name' => 'Inundaciones', 'type' => '2'],
        ];

        $categoryEntities = [];
        foreach ($categories as $c) {
            $category = $this->entityManager->getRepository(ServiceCategory::class)->findOneBy(['code' => $c['code']]);
            if (!$category) {
                $category = new ServiceCategory();
                $category->setCode($c['code']);
                $category->setName($c['name']);
                $category->setType($typeEntities[$c['type']]);
                $this->entityManager->persist($category);
            }
            $categoryEntities[$c['code']] = $category;
        }

        // 3. Seed Service Subcategories
        $subcategories = [
            ['code' => '1.1.1', 'name' => 'Carreras Populares', 'category' => '1.1'],
            ['code' => '1.1.2', 'name' => 'Ciclismo', 'category' => '1.1'],
            ['code' => '1.2.1', 'name' => 'Conciertos', 'category' => '1.2'],
            ['code' => '1.2.2', 'name' => 'Exposiciones', 'category' => '1.2'],
        ];

        foreach ($subcategories as $s) {
            $sub = $this->entityManager->getRepository(ServiceSubcategory::class)->findOneBy(['code' => $s['code']]);
            if (!$sub) {
                $sub = new ServiceSubcategory();
                $sub->setCode($s['code']);
                $sub->setName($s['name']);
                $sub->setCategory($categoryEntities[$s['category']]);
                $this->entityManager->persist($sub);
            }
        }

        // 4. Seed Materials
        $materials = [
            ['name' => 'Botiquín', 'category' => 'Sanitario'],
            ['name' => 'DESA', 'category' => 'Sanitario'],
            ['name' => 'Camilla', 'category' => 'Sanitario'],
            ['name' => 'Walkies', 'category' => 'Comunicaciones'],
            ['name' => 'Vallas', 'category' => 'Logística'],
            ['name' => 'Carpas', 'category' => 'Logística'],
            ['name' => 'Comida', 'category' => 'Avituallamiento'],
            ['name' => 'Agua', 'category' => 'Avituallamiento'],
            ['name' => 'Raciones', 'category' => 'Avituallamiento'],
        ];

        foreach ($materials as $m) {
            $mat = $this->entityManager->getRepository(Material::class)->findOneBy(['name' => $m['name']]);
            if (!$mat) {
                $mat = new Material();
                $mat->setName($m['name']);
                $mat->setCategory($m['category']);
                $this->entityManager->persist($mat);
            }
        }

        // 5. Seed Vehicles
        $vehicleData = [
            ['make' => 'Toyota', 'model' => 'Land Cruiser', 'plate' => '1234BBB', 'type' => 'Coche urbano', 'alias' => 'Urbano 1'],
            ['make' => 'Ford', 'model' => 'Ranger', 'plate' => '5678CCC', 'type' => 'Pickup', 'alias' => 'Rescate 1'],
            ['make' => 'Yamaha', 'model' => 'XT660', 'plate' => '9012DDD', 'type' => 'Moto', 'alias' => 'Moto 1'],
            ['make' => 'Zodiac', 'model' => 'Pro', 'plate' => '3456EEE', 'type' => 'Embarcación', 'alias' => 'Lancha 1'],
            ['make' => 'Orbea', 'model' => 'Wild', 'plate' => '7890FFF', 'type' => 'Bicicleta', 'alias' => 'Bici 1'],
            ['make' => 'Renault', 'model' => 'Master', 'plate' => '1111AAA', 'type' => 'Ambulancia', 'alias' => 'SVB 1'],
        ];

        foreach ($vehicleData as $v) {
            $veh = $this->entityManager->getRepository(\App\Entity\Vehicle::class)->findOneBy(['licensePlate' => $v['plate']]);
            if (!$veh) {
                $veh = new \App\Entity\Vehicle();
                $veh->setMake($v['make']);
                $veh->setModel($v['model']);
                $veh->setLicensePlate($v['plate']);
                $veh->setType($v['type']);
                $veh->setAlias($v['alias']);
                $this->entityManager->persist($veh);
            }
        }

        $this->entityManager->flush();

        $io->success('Service hierarchy, materials, and vehicles seeded successfully!');

        return Command::SUCCESS;
    }
}
