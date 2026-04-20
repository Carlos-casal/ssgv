<?php

namespace App\Command;

use App\Entity\Material;
use App\Entity\MaterialStock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fix-stock',
    description: 'Limpia el stock fantasma negativo o en 0, y recalcula el total de los productos.',
)]
class FixStockCommand extends Command
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

        $io->title('Limpieza de Stock Fantasma');

        // 1. Clean Negative / Zero Stocks
        $stocks = $this->entityManager->getRepository(MaterialStock::class)->findAll();
        $removed = 0;
        foreach ($stocks as $s) {
            if ($s->getQuantity() <= 0) {
                // If it's 0 or negative
                $this->entityManager->remove($s);
                $removed++;
            }
        }
        $this->entityManager->flush();
        $io->success("Se han eliminado $removed filas de stock en 0 o negativo.");

        // 2. Recalculate Global Stocks
        $materials = $this->entityManager->getRepository(Material::class)->findAll();
        $updated = 0;
        foreach ($materials as $m) {
            if ($m->getNature() === Material::NATURE_CONSUMABLE) {
                $total = 0;
                foreach ($m->getStocks() as $s) {
                    // Refresh stocks manually since some were just deleted via DQL, but we used ORM remove so they're in UnitOfWork
                    if ($s->getQuantity() > 0) {
                        $total += $s->getQuantity();
                    }
                }
                if ($m->getStock() !== $total) {
                    $m->setStock($total);
                    $updated++;
                }
            } else {
                $total = count($m->getUnits());
                if ($m->getStock() !== $total) {
                    $m->setStock($total);
                    $updated++;
                }
            }
        }
        $this->entityManager->flush();
        $io->success("Se actualizó la contabilidad global de $updated productos.");

        return Command::SUCCESS;
    }
}
