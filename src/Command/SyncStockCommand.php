<?php

namespace App\Command;

use App\Entity\Material;
use App\Entity\MaterialStock;
use App\Entity\MaterialUnit;
use App\Entity\MaterialBatch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-stock',
    description: 'Reconciles MaterialStock records with physical MaterialUnits and MaterialBatches.',
)]
class SyncStockCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Synchronizing Inventory Stock Records');

        // 1. Clear ALL existing stock records to start fresh
        // (Only for those with nature TECHNICAL as they are unit-based)
        $io->section('Cleaning technical equipment stock records...');
        $materials = $this->entityManager->getRepository(Material::class)->findAll();

        foreach ($materials as $material) {
            if ($material->getNature() === Material::NATURE_TECHNICAL) {
                // Delete all stock records for this technical material
                foreach ($material->getStocks() as $s) {
                    $this->entityManager->remove($s);
                }
            }
        }
        $this->entityManager->flush();

        // 2. Rebuild Stock from MaterialUnits
        $io->section('Rebuilding stock from physical units...');
        $units = $this->entityManager->getRepository(MaterialUnit::class)->findAll();
        $count = 0;

        foreach ($units as $unit) {
            $material = $unit->getMaterial();
            $location = $unit->getLocation();

            if (!$location) continue;

            $stock = $this->entityManager->getRepository(MaterialStock::class)->findOneBy([
                'material' => $material,
                'location' => $location,
                'size' => 'UNICA'
            ]);

            if (!$stock) {
                $stock = new MaterialStock();
                $stock->setMaterial($material);
                $stock->setLocation($location);
                $stock->setSize('UNICA');
                $stock->setQuantity(0);
                $this->entityManager->persist($stock);
            }

            $stock->setQuantity($stock->getQuantity() + 1);
            $count++;
        }

        $this->entityManager->flush();
        $io->success(sprintf('Synchronized %d technical units into stock records.', $count));

        // 3. Re-calculate Global Material Stock
        $io->section('Updating master material total stock counts...');
        foreach ($materials as $material) {
            $total = 0;
            foreach ($material->getStocks() as $s) {
                $total += $s->getQuantity();
            }
            $material->setStock($total);
        }
        $this->entityManager->flush();

        $io->success('All stock records have been synchronized.');

        return Command::SUCCESS;
    }
}
