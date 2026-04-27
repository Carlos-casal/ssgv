<?php

namespace App\Command;

use App\Entity\ServiceType;
use App\Entity\ServiceCategory;
use App\Entity\ServiceSubcategory;
use App\Entity\Material;
use App\Entity\MaterialUnit;
use App\Entity\KitTemplate;
use App\Entity\KitTemplateItem;
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
            ['name' => 'Botiquín', 'category' => 'Sanitario', 'nature' => Material::NATURE_TECHNICAL],
            ['name' => 'DESA', 'category' => 'Sanitario', 'nature' => Material::NATURE_TECHNICAL],
            ['name' => 'Camilla', 'category' => 'Sanitario', 'nature' => Material::NATURE_TECHNICAL],
            ['name' => 'Walkies', 'category' => 'Comunicaciones', 'nature' => Material::NATURE_TECHNICAL],
            ['name' => 'Vallas', 'category' => 'Logística', 'nature' => Material::NATURE_CONSUMABLE, 'stock' => 50, 'safety' => 10],
            ['name' => 'Carpas', 'category' => 'Logística', 'nature' => Material::NATURE_TECHNICAL],
            ['name' => 'Gasas estériles', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE, 'stock' => 100, 'safety' => 35],
            ['name' => 'Chaleco Salvavidas', 'category' => 'Mar', 'nature' => Material::NATURE_CONSUMABLE, 'stock' => 15, 'safety' => 5],
            ['name' => 'Zodiac', 'category' => 'Mar', 'nature' => Material::NATURE_TECHNICAL],
            ['name' => 'Pantalón Uniforme', 'category' => 'Uniformidad', 'nature' => Material::NATURE_CONSUMABLE, 'stock' => 30, 'safety' => 10],
            ['name' => 'Linterna', 'category' => 'Varios', 'nature' => Material::NATURE_TECHNICAL],
            // New Official Kit Materials
            ['name' => 'Tensiómetro', 'category' => 'Sanitario', 'nature' => Material::NATURE_TECHNICAL],
            ['name' => 'Pulsioxímetro', 'category' => 'Sanitario', 'nature' => Material::NATURE_TECHNICAL],
            ['name' => 'Cánula Guedel #0', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Cánula Guedel #1', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Cánula Guedel #2', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Cánula Guedel #3', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Cánula Guedel #5', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Suero fisiológico 10 ml', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Suero fisiológico 30 ml', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Suero fisiológico 100 ml', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Venda crepe 4x5', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Venda crepe 4x7', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Venda crepe 4x10', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Venda crepe 10x10', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Esparadrapo hipoalergénico', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Omnifix', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Clorhexidina', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Agua oxigenada', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Alcohol 96', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Apósitos 7x5', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Apósitos 10x8', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Apósitos 20x10', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'AMBU + mascarilla', 'category' => 'Sanitario', 'nature' => Material::NATURE_TECHNICAL],
            ['name' => 'Manta de Emergencia', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Tijera corta ropa', 'category' => 'Sanitario', 'nature' => Material::NATURE_TECHNICAL],
            ['name' => 'Pinza', 'category' => 'Sanitario', 'nature' => Material::NATURE_TECHNICAL],
            ['name' => 'Spray de frío', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
            ['name' => 'Guantes (diferentes tallas)', 'category' => 'Sanitario', 'nature' => Material::NATURE_CONSUMABLE],
        ];

        $materialMap = [];
        foreach ($materials as $m) {
            $mat = $this->entityManager->getRepository(Material::class)->findOneBy(['name' => $m['name']]);
            if (!$mat) {
                $mat = new Material();
                $mat->setName($m['name']);
                $mat->setCategory($m['category']);
                $mat->setNature($m['nature'] ?? Material::NATURE_CONSUMABLE);
                $mat->setStock($m['stock'] ?? 0);
                $mat->setSafetyStock($m['safety'] ?? 0);
                $this->entityManager->persist($mat);

                // If technical, add some units
                if ($mat->getNature() === Material::NATURE_TECHNICAL) {
                    for ($i = 1; $i <= 3; $i++) {
                        $unit = new MaterialUnit();
                        $unit->setMaterial($mat);
                        $unit->setSerialNumber($mat->getName() . '-0' . $i);
                        $this->entityManager->persist($unit);
                    }
                }
            }
            $materialMap[$m['name']] = $mat;
        }

        // 4b. Seed Official Kit Template
        $templateName = 'Mochila SVB Básica (Oficial)';
        $template = $this->entityManager->getRepository(KitTemplate::class)->findOneBy(['name' => $templateName]);
        if (!$template) {
            $template = new KitTemplate();
            $template->setName($templateName);
            $template->setContainerType('Mochila');
            $template->setDescription('Plantilla oficial para Mochilas de Soporte Vital Básico.');
            $this->entityManager->persist($template);

            $kitItems = [
                ['name' => 'Tensiómetro', 'qty' => 1],
                ['name' => 'Pulsioxímetro', 'qty' => 1],
                ['name' => 'Cánula Guedel #0', 'qty' => 1],
                ['name' => 'Cánula Guedel #1', 'qty' => 1],
                ['name' => 'Cánula Guedel #2', 'qty' => 1],
                ['name' => 'Cánula Guedel #3', 'qty' => 1],
                ['name' => 'Cánula Guedel #5', 'qty' => 1],
                ['name' => 'Gasas estériles', 'qty' => 35],
                ['name' => 'Suero fisiológico 10 ml', 'qty' => 1],
                ['name' => 'Suero fisiológico 30 ml', 'qty' => 3],
                ['name' => 'Suero fisiológico 100 ml', 'qty' => 1],
                ['name' => 'Venda crepe 4x5', 'qty' => 1],
                ['name' => 'Venda crepe 4x7', 'qty' => 3],
                ['name' => 'Venda crepe 4x10', 'qty' => 1],
                ['name' => 'Venda crepe 10x10', 'qty' => 1],
                ['name' => 'Esparadrapo hipoalergénico', 'qty' => 1],
                ['name' => 'Omnifix', 'qty' => 1],
                ['name' => 'Clorhexidina', 'qty' => 1],
                ['name' => 'Agua oxigenada', 'qty' => 1],
                ['name' => 'Alcohol 96', 'qty' => 1],
                ['name' => 'Apósitos 7x5', 'qty' => 2],
                ['name' => 'Apósitos 10x8', 'qty' => 5],
                ['name' => 'Apósitos 20x10', 'qty' => 5],
                ['name' => 'AMBU + mascarilla', 'qty' => 1],
                ['name' => 'Manta de Emergencia', 'qty' => 4],
                ['name' => 'Tijera corta ropa', 'qty' => 1],
                ['name' => 'Pinza', 'qty' => 1],
                ['name' => 'Spray de frío', 'qty' => 1],
                ['name' => 'Guantes (diferentes tallas)', 'qty' => 6],
            ];

            foreach ($kitItems as $itemData) {
                $item = new KitTemplateItem();
                if (isset($materialMap[$itemData['name']])) {
                    $item->setMaterial($materialMap[$itemData['name']]);
                } else {
                    $item->setSuggestedName($itemData['name']);
                }
                $item->setQuantity($itemData['qty']);
                $item->setTemplate($template);
                $this->entityManager->persist($item);
            }
        }

        // 5. Seed Vehicles
        $vehicleData = [
            ['make' => 'Toyota', 'model' => 'Land Cruiser', 'plate' => '1234BBB', 'type' => 'Coche urbano', 'alias' => 'Urbano 1'],
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

        $io->success('Service hierarchy, materials (with nature), and vehicles seeded successfully!');

        return Command::SUCCESS;
    }
}
