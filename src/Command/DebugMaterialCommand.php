<?php

namespace App\Command;

use App\Entity\Material;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:debug-material',
    description: 'Debugs a specific material by ID',
)]
class DebugMaterialCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'Material ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument('id');
        $material = $this->em->getRepository(Material::class)->find($id);

        if (!$material) {
            $output->writeln("Material not found.");
            return Command::FAILURE;
        }

        $output->writeln("Material ID: " . $material->getId());
        $output->writeln("Name: " . $material->getName());
        $output->writeln("Nature: " . $material->getNature());
        $output->writeln("Total Stock: " . $material->getStock());

        $units = $material->getUnits();
        $output->writeln("Units count: " . count($units));
        foreach ($units as $index => $unit) {
            $output->writeln(sprintf(
                "  Unit %d: ID=%d, Alias=%s, SN=%s, BrandModel=%s",
                $index + 1,
                $unit->getId(),
                $unit->getAlias() ?? 'null',
                $unit->getSerialNumber() ?? 'null',
                $unit->getBrandModel() ?? 'null'
            ));
        }

        $batches = $material->getBatches();
        $output->writeln("Batches count: " . count($batches));
        foreach ($batches as $index => $batch) {
            $output->writeln(sprintf(
                "  Batch %d: ID=%d, Number=%s, Stock=%d",
                $index + 1,
                $batch->getId(),
                $batch->getBatchNumber(),
                $batch->getUnitsPerPackage() * $batch->getNumPackages()
            ));
        }

        return Command::SUCCESS;
    }
}
