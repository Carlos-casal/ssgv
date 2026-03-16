<?php

namespace App\Command;

use App\Entity\Material;
use App\Entity\MaterialBatch;
use App\Entity\MaterialStock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:migrate-material-batches',
    description: 'Migrates single batch data from Material entity to MaterialBatch entity',
)]
class MigrateMaterialBatchesCommand extends Command
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
        $materialRepository = $this->entityManager->getRepository(Material::class);
        $materials = $materialRepository->findAll();

        $count = 0;
        foreach ($materials as $material) {
            // Only migrate if it's a consumable and has some batch data or stock
            if ($material->getNature() === Material::NATURE_CONSUMABLE) {

                // If the material already has batches, skip it to avoid duplication if run twice
                if (!$material->getBatches()->isEmpty()) {
                    continue;
                }

                $batch = new MaterialBatch();
                $batch->setMaterial($material);
                $batch->setBatchNumber($material->getBatchNumber() ?? 'LOTE-MIGRADO');
                $batch->setExpirationDate($material->getExpirationDate());
                $batch->setSupplier($material->getSupplier());
                $batch->setUnitsPerPackage($material->getUnitsPerPackage() ?? 1);

                // Estimate numPackages from total stock
                $unitsPerPackage = $material->getUnitsPerPackage() ?: 1;
                $numPackages = ceil($material->getStock() / $unitsPerPackage);
                $batch->setNumPackages((int)$numPackages);

                $batch->setUnitPrice($material->getUnitPrice());
                $batch->setTotalPrice($material->getTotalPrice());
                $batch->setIva($material->getIva());
                $batch->setMarginPercentage($material->getMarginPercentage());

                $this->entityManager->persist($batch);

                // Update existing stocks to point to this new batch
                foreach ($material->getStocks() as $stock) {
                    $stock->setBatch($batch);
                }

                $count++;
            }
        }

        $this->entityManager->flush();

        $io->success(sprintf('Migrated %d materials to the new batch structure.', $count));

        return Command::SUCCESS;
    }
}
