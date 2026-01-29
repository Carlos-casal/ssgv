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
    description: 'Seeds the service hierarchy (Type > Category > Subcategory) and Materials',
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

        // Hierachy
        $data = [
            '1' => ['name' => 'PREVENTIVOS', 'categories' => [
                '1.1' => ['name' => 'DEPORTIVOS', 'subs' => [
                    '1.1.1' => 'Fútbol',
                    '1.1.2' => 'Carreras / Maratones',
                    '1.1.3' => 'Ciclismo',
                ]],
                '1.2' => ['name' => 'CULTURALES', 'subs' => [
                    '1.2.1' => 'Conciertos',
                    '1.2.2' => 'Teatros',
                ]],
            ]],
            '2' => ['name' => 'EMERGENCIAS', 'categories' => [
                '2.1' => ['name' => 'INCENDIOS', 'subs' => [
                    '2.1.1' => 'Forestales',
                    '2.1.2' => 'Urbanos',
                ]],
            ]],
            '3' => ['name' => 'SOCIAL', 'categories' => [
                '3.1' => ['name' => 'REPARTO', 'subs' => [
                    '3.1.1' => 'Alimentos',
                    '3.1.2' => 'Ropa',
                ]],
            ]],
        ];

        foreach ($data as $tCode => $tInfo) {
            $type = $this->entityManager->getRepository(ServiceType::class)->findOneBy(['codigo' => $tCode]) ?? new ServiceType();
            $type->setCodigo($tCode)->setName($tInfo['name']);
            $this->entityManager->persist($type);

            foreach ($tInfo['categories'] as $cCode => $cInfo) {
                $cat = $this->entityManager->getRepository(ServiceCategory::class)->findOneBy(['codigo' => $cCode]) ?? new ServiceCategory();
                $cat->setCodigo($cCode)->setName($cInfo['name'])->setServiceType($type);
                $this->entityManager->persist($cat);

                foreach ($cInfo['subs'] as $sCode => $sName) {
                    $sub = $this->entityManager->getRepository(ServiceSubcategory::class)->findOneBy(['codigo' => $sCode]) ?? new ServiceSubcategory();
                    $sub->setCodigo($sCode)->setName($sName)->setServiceCategory($cat);
                    $this->entityManager->persist($sub);
                }
            }
        }

        // Materials
        $materials = [
            ['name' => 'Botiquín', 'category' => 'sanitario'],
            ['name' => 'DESA', 'category' => 'sanitario'],
            ['name' => 'Camilla', 'category' => 'sanitario'],
            ['name' => 'Walkies', 'category' => 'comunicaciones'],
            ['name' => 'Emisora VHF', 'category' => 'comunicaciones'],
            ['name' => 'Vallas', 'category' => 'logistica'],
            ['name' => 'Carpas', 'category' => 'logistica'],
        ];

        foreach ($materials as $m) {
            $mat = $this->entityManager->getRepository(Material::class)->findOneBy(['name' => $m['name']]) ?? new Material();
            $mat->setName($m['name'])->setCategory($m['category']);
            $this->entityManager->persist($mat);
        }

        $this->entityManager->flush();
        $io->success('Service hierarchy and Materials seeded successfully.');

        return Command::SUCCESS;
    }
}
