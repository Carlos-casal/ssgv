<?php

namespace App\Service;

use App\Entity\Material;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Shared\Date;
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
    public function previewImport(UploadedFile $file): array
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
            $nature = $this->getCellValue($worksheet, "B", $row);
            $category = $this->getCellValue($worksheet, "C", $row);
            $barcode = $this->getCellValue($worksheet, "E", $row);
            $unitsPerPackage = (int)$this->getCellValue($worksheet, "I", $row) ?: 1;
            $numPackages = (int)$this->getCellValue($worksheet, "J", $row);
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
    public function processImport(UploadedFile $file): array
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
                $nature = $this->getCellValue($worksheet, "B", $row);
                $category = $this->getCellValue($worksheet, "C", $row);
                $subFamily = $this->getCellValue($worksheet, "D", $row);
                $barcode = $this->getCellValue($worksheet, "E", $row);
                $batchNumber = $this->getCellValue($worksheet, "F", $row);
                $expirationDate = $this->getDateValue($worksheet, "G", $row);
                $supplier = $this->getCellValue($worksheet, "H", $row);
                $unitsPerPackage = (int)$this->getCellValue($worksheet, "I", $row) ?: 1;
                $numPackages = (int)$this->getCellValue($worksheet, "J", $row);
                $totalPrice = $this->getCellValue($worksheet, "K", $row);
                $discountPct = $this->getCellValue($worksheet, "L", $row);
                // Margin in M (13) - currently not used in entity
                $brandModel = $this->getCellValue($worksheet, "N", $row);
                $serialNumber = $this->getCellValue($worksheet, "O", $row);
                $alias = $this->getCellValue($worksheet, "P", $row);
                $purchaseDate = $this->getDateValue($worksheet, "Q", $row);
                $warrantyEndDate = $this->getDateValue($worksheet, "R", $row);
                $description = $this->getCellValue($worksheet, "S", $row);

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
                if ($nature) $material->setNature($nature);
                if ($category) $material->setCategory($category);
                if ($subFamily) $material->setSubFamily($subFamily);
                if ($barcode && $barcode !== 'S/N') $material->setBarcode($barcode);
                if ($batchNumber) $material->setBatchNumber($batchNumber);
                if ($expirationDate) $material->setExpirationDate($expirationDate);
                if ($supplier) $material->setSupplier($supplier);
                $material->setUnitsPerPackage($unitsPerPackage);
                if ($totalPrice) $material->setTotalPrice($totalPrice);
                if ($discountPct) $material->setDiscountPercentage($discountPct);
                if ($brandModel) $material->setBrandModel($brandModel);
                if ($purchaseDate) $material->setPurchaseDate($purchaseDate);
                if ($warrantyEndDate) $material->setWarrantyEndDate($warrantyEndDate);
                if ($description) $material->setDescription($description);

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
                                'discountPct' => $discountPct,
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
        $value = $cell->getValue();

        if ($cell->getDataType() === \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC) {
            // Check if it's formatted as a date
            if (Date::isDateTime($cell)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
        }

        return $value;
    }

    private function getDateValue($worksheet, $col, $row): ?\DateTimeImmutable
    {
        $value = $worksheet->getCell($col . $row)->getValue();
        if (empty($value)) return null;

        if (is_numeric($value)) {
            return \DateTimeImmutable::createFromMutable(Date::excelToDateTimeObject($value));
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
            'A1' => 'Nombre *',
            'B1' => 'Naturaleza (CONSUMIBLE/EQUIPO_TEC)',
            'C1' => 'Categoría',
            'D1' => 'Subfamilia',
            'E1' => 'Código de Barras',
            'F1' => 'Lote (Consumibles)',
            'G1' => 'Fecha Caducidad (AAAA-MM-DD)',
            'H1' => 'Proveedor',
            'I1' => 'Uds por Envase',
            'J1' => 'Nº Envases (Stock Inicial)',
            'K1' => 'Coste Total Compra (sin IVA)',
            'L1' => '% Descuento',
            'M1' => 'Margen (%)',
            'N1' => 'Marca/Modelo (Equipamiento)',
            'O1' => 'Nº Serie (S/N)',
            'P1' => 'Alias (Equipo)',
            'Q1' => 'Fecha Compra (AAAA-MM-DD)',
            'R1' => 'Fin Garantía (AAAA-MM-DD)',
            'S1' => 'Descripción'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Style headers
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']]
        ];
        $sheet->getStyle('A1:S1')->applyFromArray($headerStyle);
        
        // Add example data
        $sheet->setCellValue('A2', 'Pulse Oximeter MD300C6');
        $sheet->setCellValue('B2', 'EQUIPO_TECNICO');
        $sheet->setCellValue('C2', 'Sanitario');
        $sheet->setCellValue('D2', 'Constantes');
        $sheet->setCellValue('I2', '1');
        $sheet->setCellValue('J2', '1');
        $sheet->setCellValue('N2', 'Pulsioximetro de dedo');
        $sheet->setCellValue('O2', 'SN123456789');
        $sheet->setCellValue('P2', 'Pulsi 01');
        $sheet->setCellValue('Q2', '2024-01-15');

        $sheet->setCellValue('A3', 'Canula de Guedel');
        $sheet->setCellValue('B3', 'CONSUMIBLE');
        $sheet->setCellValue('C3', 'Sanitario');
        $sheet->setCellValue('D3', 'Canula');
        $sheet->setCellValue('E3', '697209E12');
        $sheet->setCellValue('F3', '2407010903');
        $sheet->setCellValue('G3', '2028-06-01');
        $sheet->setCellValue('I3', '1');
        $sheet->setCellValue('J3', '10');
        
        // Auto-size columns
        foreach (range('A', 'S') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Save to temp file
        $tempFile = tempnam(sys_get_temp_dir(), 'material_template_') . '.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFile);
        
        return $tempFile;
    }
}
