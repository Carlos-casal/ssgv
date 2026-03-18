<?php
use App\Kernel;
use App\Service\ExcelImportService;
use Symfony\Component\HttpFoundation\File\File;

require __DIR__.'/vendor/autoload.php';

(new \Symfony\Component\Dotenv\Dotenv())->bootEnv(__DIR__.'/.env');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

$container = $kernel->getContainer();
$importService = $container->get(ExcelImportService::class);

$file = new File(__DIR__.'/var/tmp/import_69ba643a07adc.xlsx');
$preview = $importService->previewImport($file);

echo "Total Rows: " . $preview['total_rows'] . "\n";
echo "Materials Total: " . count($preview['materials']) . "\n";
foreach ($preview['materials'] as $key => $mat) {
    echo "Key: $key | Name: " . $mat['name'] . " | Barcode: " . $mat['barcode'] . " | Add: " . $mat['stock_to_add'] . "\n";
}
