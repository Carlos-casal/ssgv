<?php

namespace App\Service;

use App\Entity\Material;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ExcelImportService
{
    private EntityManagerInterface $entityManager;
    private MaterialRepository $materialRepository;
    private MaterialManager $materialManager;
    private string $materialImagesDirectory;

    public function __construct(
        EntityManagerInterface $entityManager,
        MaterialRepository $materialRepository,
        MaterialManager $materialManager,
        string $materialImagesDirectory
    ) {
        $this->entityManager = $entityManager;
        $this->materialRepository = $materialRepository;
        $this->materialManager = $materialManager;
        $this->materialImagesDirectory = $materialImagesDirectory;
    }

    /**
     * Map Excel columns to field names based on headers in first row
     */
    private function mapColumns($worksheet): array
    {
        $map = [];
        $highestColumn = $worksheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        $headers = [
            'name' => ['nombre', 'comercial', 'artículo', 'producto', 'name'],
            'barcode' => ['código', 'barras', 'ean', 'barcode', 'qr', 'barra'],
            'category' => ['categoría', 'categoria', 'familia', 'category'],
            'nature' => ['naturaleza', 'tipo', 'clase', 'nature'],
            'subFamily' => ['subfamilia', 'subfamily'],
            'unitsPerPackage' => ['unidades', 'envase', 'uds/envase', 'package'],
            'numPackages' => ['nº', 'envases', 'número', 'number'],
            'safetyStock' => ['mínimo', 'seguridad', 'crítico', 'safety'],
            'batchNumber' => ['lote', 'batch'],
            'expirationDate' => ['caducidad', 'expiration'],
            'supplier' => ['proveedor', 'supplier'],
            'totalPrice' => ['precio', 'total', 'coste', 'price'],
            'marginPct' => ['margen', 'ganancia', 'margin'],
            'iva' => ['iva', 'tax'],
            'brandModel' => ['marca', 'modelo', 'brand', 'model'],
            'alias' => ['alias'],
            'serialNumber' => ['serie', 's/n', 'serial'],
            'networkId' => ['id', 'red', 'issi', 'imei', 'network'],
            'phoneNumber' => ['teléfono', 'móvil', 'phone'],
            'purchaseDate' => ['compra', 'purchase'],
            'warrantyEndDate' => ['garantía', 'fin', 'warranty'],
            'description' => ['descripción', 'notas', 'description']
        ];

        // Search in the first 3 rows for headers (in case there's some title or empty rows)
        for ($row = 1; $row <= 3; $row++) {
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cell = $worksheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row);
                $cellValue = $cell->getValue();
                if ($cellValue instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                    $cellValue = $cellValue->getPlainText();
                }
                $cellValue = mb_strtolower(trim((string)$cellValue));
                if (empty($cellValue)) continue;

                foreach ($headers as $field => $keywords) {
                    if (isset($map[$field])) continue; // Already mapped
                    foreach ($keywords as $keyword) {
                        if (mb_strpos($cellValue, $keyword) !== false) {
                            $map[$field] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                            break;
                        }
                    }
                }
            }
            if (count($map) >= 4) break; // Found main fields, stop searching rows
        }

        return $map;
    }

    /**
     * Preview the Excel file and return statistics about what will be imported
     */
    public function previewImport(File $file): array
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        
        $map = $this->mapColumns($worksheet);

        $preview = [
            'total_rows' => 0,
            'existing_items' => [],
            'new_items' => [],
            'errors' => []
        ];

        $highestRow = $worksheet->getHighestRow();
        
        for ($row = 2; $row <= $highestRow; $row++) {
            $name = isset($map['name']) ? $this->getCellValue($worksheet, $map['name'], $row) : null;
            $barcode = isset($map['barcode']) ? $this->getCellValue($worksheet, $map['barcode'], $row) : null;
            $category = isset($map['category']) ? $this->getCellValue($worksheet, $map['category'], $row) : null;
            $nature = isset($map['nature']) ? $this->getCellValue($worksheet, $map['nature'], $row) : null;

            $unitsPerPackage = isset($map['unitsPerPackage']) ? (int)$this->getCellValue($worksheet, $map['unitsPerPackage'], $row) : 1;
            if ($unitsPerPackage <= 0) $unitsPerPackage = 1;

            $numPackages = isset($map['numPackages']) ? (int)$this->getCellValue($worksheet, $map['numPackages'], $row) : 0;
            $stock = $unitsPerPackage * $numPackages;
            
            if (empty($name)) {
                continue;
            }
            
            $preview['total_rows']++;
            
            // Check if material exists by barcode, serial number, network id or name
            $existingMaterial = null;
            if ($barcode && $barcode !== 'S/N') {
                $existingMaterial = $this->materialRepository->findOneBy(['barcode' => $barcode]);
            }

            // Check for unique technical fields if mapped
            if (!$existingMaterial && isset($map['serialNumber'])) {
                $sn = $this->getCellValue($worksheet, $map['serialNumber'], $row);
                if ($sn && $sn !== 'S/N') {
                    $existingMaterial = $this->materialRepository->findOneBy(['serialNumber' => $sn]);
                }
            }

            if (!$existingMaterial && isset($map['networkId'])) {
                $nid = $this->getCellValue($worksheet, $map['networkId'], $row);
                if ($nid && $nid !== 'S/N') {
                    $existingMaterial = $this->materialRepository->findOneBy(['networkId' => $nid]);
                }
            }

            if (!$existingMaterial) {
                $existingMaterial = $this->materialRepository->findOneBy(['name' => $name]);
            }
            
            if ($existingMaterial) {
                $preview['existing_items'][] = [
                    'name' => $name,
                    'barcode' => $barcode,
                    'current_stock' => $existingMaterial->getStock(),
                    'stock_to_add' => $stock
                ];
            } else {
                $preview['new_items'][] = [
                    'name' => $name,
                    'barcode' => $barcode,
                    'category' => $category,
                    'nature' => $nature,
                    'initial_stock' => $stock
                ];
            }
        }
        
        return $preview;
    }

    /**
     * Process the Excel import and create/update materials
     */
    public function processImport(File $file): array
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        
        $map = $this->mapColumns($worksheet);

        $result = [
            'created' => 0,
            'updated' => 0,
            'errors' => []
        ];

        // Extract images from Excel
        $images = $this->extractImagesFromWorksheet($worksheet);
        
        $highestRow = $worksheet->getHighestRow();
        
        for ($row = 2; $row <= $highestRow; $row++) {
            try {
                $name = isset($map['name']) ? $this->getCellValue($worksheet, $map['name'], $row) : null;
                $barcode = isset($map['barcode']) ? $this->getCellValue($worksheet, $map['barcode'], $row) : null;
                $category = isset($map['category']) ? $this->getCellValue($worksheet, $map['category'], $row) : null;
                $nature = isset($map['nature']) ? $this->getCellValue($worksheet, $map['nature'], $row) : null;
                $subFamily = isset($map['subFamily']) ? $this->getCellValue($worksheet, $map['subFamily'], $row) : null;

                $unitsPerPackage = isset($map['unitsPerPackage']) ? (int)$this->getCellValue($worksheet, $map['unitsPerPackage'], $row) : 1;
                if ($unitsPerPackage <= 0) $unitsPerPackage = 1;

                $numPackages = isset($map['numPackages']) ? (int)$this->getCellValue($worksheet, $map['numPackages'], $row) : 0;

                $safetyStock = isset($map['safetyStock']) ? (int)$this->getCellValue($worksheet, $map['safetyStock'], $row) : 0;
                $batchNumber = isset($map['batchNumber']) ? $this->getCellValue($worksheet, $map['batchNumber'], $row) : null;
                $expirationDate = isset($map['expirationDate']) ? $this->getDateValue($worksheet, $map['expirationDate'], $row) : null;
                $supplier = isset($map['supplier']) ? $this->getCellValue($worksheet, $map['supplier'], $row) : null;
                $totalPrice = isset($map['totalPrice']) ? $this->getCellValue($worksheet, $map['totalPrice'], $row) : null;
                $marginPct = isset($map['marginPct']) ? $this->getCellValue($worksheet, $map['marginPct'], $row) : null;
                $iva = isset($map['iva']) ? $this->getCellValue($worksheet, $map['iva'], $row) : null;
                $brandModel = isset($map['brandModel']) ? $this->getCellValue($worksheet, $map['brandModel'], $row) : null;
                $alias = isset($map['alias']) ? $this->getCellValue($worksheet, $map['alias'], $row) : null;
                $serialNumber = isset($map['serialNumber']) ? $this->getCellValue($worksheet, $map['serialNumber'], $row) : null;
                $networkId = isset($map['networkId']) ? $this->getCellValue($worksheet, $map['networkId'], $row) : null;
                $phoneNumber = isset($map['phoneNumber']) ? $this->getCellValue($worksheet, $map['phoneNumber'], $row) : null;
                $purchaseDate = isset($map['purchaseDate']) ? $this->getDateValue($worksheet, $map['purchaseDate'], $row) : null;
                $warrantyEndDate = isset($map['warrantyEndDate']) ? $this->getDateValue($worksheet, $map['warrantyEndDate'], $row) : null;
                $description = isset($map['description']) ? $this->getCellValue($worksheet, $map['description'], $row) : null;

                if (empty($name)) {
                    continue;
                }

                // Find or Create Material
                $material = null;
                if ($barcode && $barcode !== 'S/N') {
                    $material = $this->materialRepository->findOneBy(['barcode' => $barcode]);
                }

                if (!$material && $serialNumber && $serialNumber !== 'S/N') {
                    $material = $this->materialRepository->findOneBy(['serialNumber' => $serialNumber]);
                }

                if (!$material && $networkId && $networkId !== 'S/N') {
                    $material = $this->materialRepository->findOneBy(['networkId' => $networkId]);
                }

                if (!$material) {
                    $material = $this->materialRepository->findOneBy(['name' => $name]);
                }
                
                $isNew = false;
                if (!$material) {
                    $material = new Material();
                    $material->setName($name);
                    $this->entityManager->persist($material);
                    $isNew = true;
                    $result['created']++;
                } else {
                    $result['updated']++;
                }

                // Update Material fields
                if ($isNew) {
                    if ($nature) $material->setNature($nature);
                    if ($category) $material->setCategory($category);
                    if ($subFamily) $material->setSubFamily($subFamily);
                    if ($barcode && $barcode !== 'S/N') $material->setBarcode($barcode);
                    if ($safetyStock) $material->setSafetyStock($safetyStock);
                    if ($batchNumber) $material->setBatchNumber($batchNumber);
                    if ($expirationDate) $material->setExpirationDate($expirationDate);
                    if ($supplier) $material->setSupplier($supplier);
                    $material->setUnitsPerPackage($unitsPerPackage);
                    if ($totalPrice) $material->setTotalPrice($totalPrice);
                    if ($marginPct) $material->setMarginPercentage($marginPct);
                    if ($iva) $material->setIva($iva);
                    if ($brandModel) $material->setBrandModel($brandModel);
                    if ($networkId) $material->setNetworkId($networkId);
                    if ($phoneNumber) $material->setPhoneNumber($phoneNumber);
                    if ($purchaseDate) $material->setPurchaseDate($purchaseDate);
                    if ($warrantyEndDate) $material->setWarrantyEndDate($warrantyEndDate);
                    if ($description) $material->setDescription($description);
                }

                // Handle Image
                if (isset($images[$row])) {
                    $imagePath = $this->saveImage($images[$row]);
                    $material->setImagePath($imagePath);
                }

                // Handle Stock, Batches and Units
                if ($material->getNature() === Material::NATURE_TECHNICAL) {
                    if ($serialNumber && $serialNumber !== 'S/N') {
                        // Check if unit exists
                        $unit = $this->entityManager->getRepository(\App\Entity\MaterialUnit::class)->findOneBy(['serialNumber' => $serialNumber]);
                        if (!$unit) {
                            $this->materialManager->createUnit($material, [
                                'serialNumber' => $serialNumber,
                                'alias' => $alias,
                                'brandModel' => $brandModel,
                                'purchasePrice' => $totalPrice, // Simplified: total price per unit
                                'discountPct' => $marginPct, // Use margin for units if discount is not separate
                                'networkId' => $networkId,
                                'phoneNumber' => $phoneNumber,
                            ]);
                        }
                    } else {
                        // Technical equipment without serial number (bulk)
                        $this->materialManager->updateStockDirectly($material, $this->materialManager->getCentralWarehouse(), $unitsPerPackage * $numPackages);
                    }
                } else {
                    // Consumable - Create or Update Batch
                    $batch = null;
                    if ($batchNumber) {
                        $batch = $this->entityManager->getRepository(\App\Entity\MaterialBatch::class)->findOneBy([
                            'material' => $material,
                            'batchNumber' => $batchNumber
                        ]);
                    }

                    if (!$batch) {
                        $batch = new \App\Entity\MaterialBatch();
                        $batch->setMaterial($material);
                        $batch->setBatchNumber($batchNumber ?? 'LOTE-EXCEL');
                        $this->entityManager->persist($batch);
                    }

                    if ($expirationDate) $batch->setExpirationDate($expirationDate);
                    if ($supplier) $batch->setSupplier($supplier);
                    $batch->setUnitsPerPackage($unitsPerPackage);

                    // Add to current numPackages if it already exists
                    $batch->setNumPackages($batch->getNumPackages() + $numPackages);

                    if ($totalPrice) $batch->setTotalPrice($totalPrice);
                    if ($marginPct) $batch->setMarginPercentage($marginPct);
                    $batch->setIva($iva ?? $material->getIva());

                    // Unit price calculation
                    $totalStockInBatch = $batch->getUnitsPerPackage() * $batch->getNumPackages();
                    if ($totalStockInBatch > 0 && $batch->getTotalPrice()) {
                        $priceVal = (float)$batch->getTotalPrice();
                        if ($batch->getMarginPercentage()) {
                            $priceVal = $priceVal - ($priceVal * ((float)$batch->getMarginPercentage() / 100));
                        }
                        $batch->setUnitPrice((string)($priceVal / $totalStockInBatch));
                    }

                    $this->materialManager->updateStockWithBatch($material, $this->materialManager->getCentralWarehouse(), $unitsPerPackage * $numPackages, $batch);
                }

            } catch (\Exception $e) {
                $result['errors'][] = "Fila {$row}: " . $e->getMessage();
            }
        }
        
        $this->entityManager->flush();
        
        return $result;
    }

    private function getCellValue($worksheet, $col, $row)
    {
        $cell = $worksheet->getCell($col . $row);
        $value = $cell->getValue();

        // Handle RichText
        if ($value instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
            $value = $value->getPlainText();
        }

        // If it's a formula or empty, try calculated value
        if (is_string($value) && strpos($value, '=') === 0 || $value === null || $value === '') {
            try {
                $value = $cell->getCalculatedValue();
            } catch (\Exception $e) {
                // Keep the raw value if calculation fails
            }
        }

        // Fallback to formatted value if still empty (useful for some Excel formats)
        if ($value === null || $value === '') {
            $value = $cell->getFormattedValue();
        }

        // Handle Dates specifically if it's a numeric type or looks like a date
        if ($cell->getDataType() === \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC || Date::isDateTime($cell)) {
            if (Date::isDateTime($cell)) {
                try {
                    return Date::excelToDateTimeObject($value)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Not a valid date after all
                }
            }
        }

        return is_string($value) ? trim($value) : $value;
    }

    private function getDateValue($worksheet, $col, $row): ?\DateTimeImmutable
    {
        $cell = $worksheet->getCell($col . $row);
        $value = $cell->getValue();
        if (empty($value)) return null;

        if (is_numeric($value)) {
            return \DateTimeImmutable::createFromMutable(Date::excelToDateTimeObject($value));
        }

        // Try common Spanish formats
        $formats = ['d/m/Y', 'd/m/y', 'd-m-Y', 'd-m-y', 'Y-m-d'];
        foreach ($formats as $format) {
            $date = \DateTimeImmutable::createFromFormat($format, $value);
            if ($date) return $date->setTime(0, 0, 0);
        }

        try {
            return new \DateTimeImmutable($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Extract images from Excel worksheet
     */
    private function extractImagesFromWorksheet($worksheet): array
    {
        $images = [];
        
        foreach ($worksheet->getDrawingCollection() as $drawing) {
            if ($drawing instanceof Drawing) {
                // Get the row where the image is located
                $coordinates = $drawing->getCoordinates();
                preg_match('/(\d+)/', $coordinates, $matches);
                $row = (int)$matches[0];
                
                // Store image data with row number
                $images[$row] = [
                    'path' => $drawing->getPath(),
                    'extension' => $drawing->getExtension()
                ];
            }
        }
        
        return $images;
    }

    /**
     * Save image to material images directory
     */
    private function saveImage(array $imageData): string
    {
        $sourcePath = $imageData['path'];
        $extension = $imageData['extension'];
        
        $newFilename = uniqid('excel_import_') . '.' . $extension;
        $destinationPath = $this->materialImagesDirectory . '/' . $newFilename;
        
        copy($sourcePath, $destinationPath);
        
        return $newFilename;
    }

    /**
     * Generate Excel template for import
     */
    public function generateTemplate(): string
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $headers = [
            'A1' => 'Nombre Comercial *',
            'B1' => 'Código de Barras *',
            'C1' => 'Categoría *',
            'D1' => 'Naturaleza * (CONSUMIBLE/EQUIPO_TECNICO)',
            'E1' => 'Subfamilia *',
            'F1' => 'Unidades por Envase *',
            'G1' => 'Nº de Envases *',
            'H1' => 'Stock Mínimo (Envases) *',
            'I1' => 'Lote *',
            'J1' => 'Fecha de Caducidad * (DD/MM/AA)',
            'K1' => 'Proveedor *',
            'L1' => 'Precio Compra Total (IVA inc.) *',
            'M1' => 'Margen (%) *',
            'N1' => 'IVA (%) *',
            'O1' => 'Marca y Modelo *',
            'P1' => 'Alias',
            'Q1' => 'Número de Serie',
            'R1' => 'ID de Red (ISSI/IMEI)',
            'S1' => 'Teléfono',
            'T1' => 'Fecha de Compra (DD/MM/AA) *',
            'U1' => 'Fin de Garantía (DD/MM/AA) *',
            'V1' => 'Descripción'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Style headers
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']]
        ];
        $sheet->getStyle('A1:V1')->applyFromArray($headerStyle);
        
        // Add example data
        $sheet->setCellValue('A2', 'Motorola DP1400');
        $sheet->setCellValue('B2', 'SN-DP1400-01');
        $sheet->setCellValue('C2', 'Comunicaciones');
        $sheet->setCellValue('D2', 'EQUIPO_TECNICO');
        $sheet->setCellValue('E2', 'Portátiles');
        $sheet->setCellValue('F2', '1');
        $sheet->setCellValue('G2', '1');
        $sheet->setCellValue('O2', 'Motorola');
        $sheet->setCellValue('P2', 'TALKI-01');
        $sheet->setCellValue('Q2', 'SN99887766');
        $sheet->setCellValue('R2', '123456');

        $sheet->setCellValue('A3', 'Paracetamol 500mg');
        $sheet->setCellValue('B3', '8470006521458');
        $sheet->setCellValue('C3', 'Sanitario');
        $sheet->setCellValue('D3', 'CONSUMIBLE');
        $sheet->setCellValue('E3', 'Analgesia');
        $sheet->setCellValue('F3', '20');
        $sheet->setCellValue('G3', '10');
        $sheet->setCellValue('I3', 'L24001');
        $sheet->setCellValue('J3', '01/01/26');
        
        // Auto-size columns
        foreach (range('A', 'V') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Save to temp file
        $tempBase = tempnam(sys_get_temp_dir(), 'material_template_');
        $tempFile = $tempBase . '.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFile);

        // Clean up the initial tempnam file (which is empty)
        if (file_exists($tempBase)) {
            unlink($tempBase);
        }
        
        return $tempFile;
    }
}
