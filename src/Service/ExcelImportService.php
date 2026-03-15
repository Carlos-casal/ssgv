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
     * Preview the Excel file and return statistics about what will be imported
     */
    public function previewImport(File $file): array
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        
        $preview = [
            'total_rows' => 0,
            'existing_items' => [],
            'new_items' => [],
            'errors' => []
        ];

        $highestRow = $worksheet->getHighestRow();
        
        for ($row = 2; $row <= $highestRow; $row++) {
            $name = $this->getCellValue($worksheet, "A", $row);
            $barcode = $this->getCellValue($worksheet, "B", $row);
            $category = $this->getCellValue($worksheet, "C", $row);
            $nature = $this->getCellValue($worksheet, "D", $row);
            $unitsPerPackage = (int)$this->getCellValue($worksheet, "F", $row) ?: 1;
            $numPackages = (int)$this->getCellValue($worksheet, "G", $row);
            $stock = $unitsPerPackage * $numPackages;
            
            if (empty($name)) {
                continue;
            }
            
            $preview['total_rows']++;
            
            // Check if material exists by barcode or name
            $existingMaterial = null;
            if ($barcode && $barcode !== 'S/N') {
                $existingMaterial = $this->materialRepository->findOneBy(['barcode' => $barcode]);
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
                $name = $this->getCellValue($worksheet, "A", $row);
                $barcode = $this->getCellValue($worksheet, "B", $row);
                $category = $this->getCellValue($worksheet, "C", $row);
                $nature = $this->getCellValue($worksheet, "D", $row);
                $subFamily = $this->getCellValue($worksheet, "E", $row);
                $unitsPerPackage = (int)$this->getCellValue($worksheet, "F", $row) ?: 1;
                $numPackages = (int)$this->getCellValue($worksheet, "G", $row);
                $safetyStock = (int)$this->getCellValue($worksheet, "H", $row);
                $batchNumber = $this->getCellValue($worksheet, "I", $row);
                $expirationDate = $this->getDateValue($worksheet, "J", $row);
                $supplier = $this->getCellValue($worksheet, "K", $row);
                $totalPrice = $this->getCellValue($worksheet, "L", $row);
                $marginPct = $this->getCellValue($worksheet, "M", $row);
                $iva = $this->getCellValue($worksheet, "N", $row);
                $brandModel = $this->getCellValue($worksheet, "O", $row);
                $alias = $this->getCellValue($worksheet, "P", $row);
                $serialNumber = $this->getCellValue($worksheet, "Q", $row);
                $networkId = $this->getCellValue($worksheet, "R", $row);
                $phoneNumber = $this->getCellValue($worksheet, "S", $row);
                $purchaseDate = $this->getDateValue($worksheet, "T", $row);
                $warrantyEndDate = $this->getDateValue($worksheet, "U", $row);
                $description = $this->getCellValue($worksheet, "V", $row);

                if (empty($name)) {
                    continue;
                }

                // Find or Create Material
                $material = null;
                if ($barcode && $barcode !== 'S/N') {
                    $material = $this->materialRepository->findOneBy(['barcode' => $barcode]);
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

                // Handle Stock and Units
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
                    // Consumable
                    $this->materialManager->updateStockDirectly($material, $this->materialManager->getCentralWarehouse(), $unitsPerPackage * $numPackages);
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
        $value = $cell->getCalculatedValue();

        if ($value instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
            $value = $value->getPlainText();
        }

        if ($cell->getDataType() === \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC) {
            // Check if it's formatted as a date
            if (Date::isDateTime($cell)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
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
            'C1' => 'Categoría',
            'D1' => 'Naturaleza (CONSUMIBLE/EQUIPO_TECNICO)',
            'E1' => 'Subfamilia',
            'F1' => 'Unidades por Envase',
            'G1' => 'Nº de Envases',
            'H1' => 'Stock Mínimo (Envases)',
            'I1' => 'Lote',
            'J1' => 'Fecha de Caducidad (DD/MM/AA)',
            'K1' => 'Proveedor',
            'L1' => 'Precio Compra Total (IVA inc.)',
            'M1' => 'Margen (%)',
            'N1' => 'IVA (%)',
            'O1' => 'Marca y Modelo',
            'P1' => 'Alias',
            'Q1' => 'Número de Serie',
            'R1' => 'ID de Red (ISSI/IMEI)',
            'S1' => 'Teléfono',
            'T1' => 'Fecha de Compra (DD/MM/AA)',
            'U1' => 'Fin de Garantía (DD/MM/AA)',
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
        $tempFile = tempnam(sys_get_temp_dir(), 'material_template_') . '.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFile);
        
        return $tempFile;
    }
}
