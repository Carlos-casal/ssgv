<?php
namespace App\Command;

use App\Service\ExcelImportService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\File;

#[AsCommand(name: 'app:test-import')]
class TestImportCommand extends Command
{
    private $importService;

    public function __construct(ExcelImportService $importService)
    {
        $this->importService = $importService;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = new File(__DIR__.'/../../var/tmp/import_69ba643a07adc.xlsx');
        $preview = $this->importService->previewImport($file);

        $output->writeln("Total Rows: " . $preview['total_rows']);
        $output->writeln("Materials Total: " . count($preview['materials']));
        
        foreach ($preview['materials'] as $key => $mat) {
            $output->writeln("Key: $key | Name: " . $mat['name'] . " | Barcode: " . $mat['barcode'] . " | Add: " . $mat['stock_to_add']);
        }
        
        return Command::SUCCESS;
    }
}
