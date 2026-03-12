<?php

namespace App\Service;

use App\Entity\Material;
use App\Entity\MaterialUnit;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
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

        // Skip header row, start from row 2
        $highestRow = $worksheet->getHighestRow();
        
        for ($row = 2; $row <= $highestRow; $row++) {
            $name = $worksheet->getCell("A{$row}")->getValue();
            $nature = $worksheet->getCell("B{$row}")->getValue();
            $barcode = $worksheet->getCell("E{$row}")->getValue();
            $category = $worksheet->getCell("C{$row}")->getValue();
            
            $unitsPerPack = (int)$worksheet->getCell("I{$row}")->getValue() ?: 1;
            $numPackages = (int)$worksheet->getCell("J{$row}")->getValue();
            $stock = $unitsPerPack * $numPackages;

            if ($nature === 'EQUIPO_TECNICO') {
                $stock = 1; // Technical equipment usually handled one by one in excel row
            }

            // Skip empty rows
            if (empty($name)) {
                continue;
            }
            
            $preview['total_rows']++;
            
            // Check if material exists by barcode or serial number
            $existingMaterial = null;
            if ($barcode) {
                $existingMaterial = $this->materialRepository->findOneBy(['barcode' => $barcode]);
            }

            if (!$existingMaterial && $nature === 'EQUIPO_TECNICO') {
                $serialNumber = $worksheet->getCell("O{$row}")->getValue();
                if ($serialNumber) {
                    $existingMaterial = $this->materialRepository->findOneBy(['serialNumber' => $serialNumber]);
                }
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
                    'initial_stock' => $stock,
                    'nature' => $nature
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
                $name = $worksheet->getCell("A{$row}")->getValue();
                if (empty($name)) continue;

                $nature = $worksheet->getCell("B{$row}")->getValue() ?: Material::NATURE_CONSUMABLE;
                $category = $worksheet->getCell("C{$row}")->getValue();
                $subfamily = $worksheet->getCell("D{$row}")->getValue();
                $barcode = $worksheet->getCell("E{$row}")->getValue();
                $batch = $worksheet->getCell("F{$row}")->getValue();
                $expiryValue = $worksheet->getCell("G{$row}")->getValue();
                $supplier = $worksheet->getCell("H{$row}")->getValue();
                $unitsPerPack = (int)$worksheet->getCell("I{$row}")->getValue() ?: 1;
                $numPacks = (int)$worksheet->getCell("J{$row}")->getValue();
                $totalPrice = $worksheet->getCell("K{$row}")->getValue();
                $discount = $worksheet->getCell("L{$row}")->getValue();
                // Column M is Margen (ignored for now as we don't have a field for it in entity yet, or maybe it maps to something else)
                $brandModel = $worksheet->getCell("N{$row}")->getValue();
                $serialNumber = (string)$worksheet->getCell("O{$row}")->getValue();
                $alias = $worksheet->getCell("P{$row}")->getValue();
                $purchaseDateValue = $worksheet->getCell("Q{$row}")->getValue();
                $warrantyDateValue = $worksheet->getCell("R{$row}")->getValue();
                $description = $worksheet->getCell("S{$row}")->getValue();

                $expiryDate = null;
                if ($expiryValue) {
                    if (is_numeric($expiryValue)) {
                        $expiryDate = \DateTimeImmutable::createFromMutable(Date::excelToDateTimeObject($expiryValue));
                    } else {
                        $expiryDate = new \DateTimeImmutable($expiryValue);
                    }
                }

                $purchaseDate = null;
                if ($purchaseDateValue) {
                    if (is_numeric($purchaseDateValue)) {
                        $purchaseDate = \DateTimeImmutable::createFromMutable(Date::excelToDateTimeObject($purchaseDateValue));
                    } else {
                        $purchaseDate = new \DateTimeImmutable($purchaseDateValue);
                    }
                }

                $warrantyDate = null;
                if ($warrantyDateValue) {
                    if (is_numeric($warrantyDateValue)) {
                        $warrantyDate = \DateTimeImmutable::createFromMutable(Date::excelToDateTimeObject($warrantyDateValue));
                    } else {
                        $warrantyDate = new \DateTimeImmutable($warrantyDateValue);
                    }
                }

                // Find or create Material (Base)
                $material = null;
                if ($barcode) {
                    $material = $this->materialRepository->findOneBy(['barcode' => $barcode]);
                }
                
                if (!$material && $nature === Material::NATURE_TECHNICAL && !empty($serialNumber)) {
                     $material = $this->materialRepository->findOneBy(['serialNumber' => $serialNumber]);
                }

                $isNew = false;
                if (!$material) {
                    $material = new Material();
                    $material->setName($name);
                    $material->setNature($nature);
                    $material->setCategory($category);
                    $material->setSubFamily($subfamily);
                    $material->setBarcode($barcode);
                    $material->setUnitsPerPackage($unitsPerPack);
                    $material->setSupplier($supplier);
                    $material->setDescription($description);
                    $material->setBrandModel($brandModel);
                    $material->setPurchaseDate($purchaseDate);
                    $material->setWarrantyEndDate($warrantyDate);

                    if (isset($images[$row])) {
                        $imagePath = $this->saveImage($images[$row]);
                        $material->setImagePath($imagePath);
                    }

                    $this->entityManager->persist($material);
                    $isNew = true;
                }

                if ($nature === Material::NATURE_TECHNICAL) {
                    // Create Unit
                    if (!empty($serialNumber)) {
                        $existingUnit = $this->entityManager->getRepository(MaterialUnit::class)->findOneBy(['serialNumber' => $serialNumber]);
                        if ($existingUnit) {
                            $result['errors'][] = "Fila {$row}: El número de serie {$serialNumber} ya existe.";
                            continue;
                        }
                    }

                    $this->materialManager->createUnit($material, [
                        'alias' => $alias,
                        'serialNumber' => $serialNumber,
                        'purchasePrice' => $totalPrice,
                        'discountPct' => $discount,
                    ]);

                    if ($isNew) $result['created']++; else $result['updated']++;

                } else {
                    // Consumable
                    $material->setBatchNumber($batch);
                    $material->setExpirationDate($expiryDate);

                    $stockToAdd = $numPacks * $unitsPerPack;
                    if ($stockToAdd > 0) {
                        $this->materialManager->adjustStock($material, $stockToAdd, 'Importación Excel');
                    }

                    if ($isNew) $result['created']++; else $result['updated']++;
                }

            } catch (\Exception $e) {
                $result['errors'][] = "Fila {$row}: " . $e->getMessage();
            }
        }
        
        $this->entityManager->flush();
        
        return $result;
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
            'B1' => 'Naturaleza (CONSUMIBLE/EQUIPO_TECNICO) *',
            'C1' => 'Categoría',
            'D1' => 'Subfamilia',
            'E1' => 'Código de Barras',
            'F1' => 'Lote (Consumibles)',
            'G1' => 'Fecha Caducidad (AAAA-MM-DD)',
            'H1' => 'Proveedor',
            'I1' => 'Uds por Envase',
            'J1' => 'Nº Envases (Stock Inicial)',
            'K1' => 'Coste Total Compra',
            'L1' => '% Descuento',
            'M1' => 'Margen (%)',
            'N1' => 'Marca/Modelo (Equipo)',
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
        
        // Add example data (Consumable)
        $sheet->setCellValue('A2', 'Gasas Estériles 10x10');
        $sheet->setCellValue('B2', 'CONSUMIBLE');
        $sheet->setCellValue('C2', 'Sanitario');
        $sheet->setCellValue('D2', 'Curas');
        $sheet->setCellValue('E2', '8412345678901');
        $sheet->setCellValue('F2', 'LOT-2024-001');
        $sheet->setCellValue('G2', '2026-12-31');
        $sheet->setCellValue('H2', 'SUMINISTROS MEDICOS S.A.');
        $sheet->setCellValue('I2', '5');
        $sheet->setCellValue('J2', '10');
        $sheet->setCellValue('K2', '25.50');
        $sheet->setCellValue('L2', '0');
        $sheet->setCellValue('M2', '0');
        $sheet->setCellValue('S2', 'Sobre de 5 gasas estériles');

        // Add example data (Technical Equipment)
        $sheet->setCellValue('A3', 'Monitor Desfibrilador');
        $sheet->setCellValue('B3', 'EQUIPO_TECNICO');
        $sheet->setCellValue('C3', 'Sanitario');
        $sheet->setCellValue('D3', 'Diagnóstico');
        $sheet->setCellValue('H3', 'PHILLIPS SPAIN');
        $sheet->setCellValue('K3', '4500.00');
        $sheet->setCellValue('N3', 'HeartStart FR3');
        $sheet->setCellValue('O3', 'SN-PH-99238');
        $sheet->setCellValue('P3', 'DESA-01');
        $sheet->setCellValue('Q3', '2024-01-15');
        $sheet->setCellValue('R3', '2026-01-15');
        $sheet->setCellValue('S3', 'Equipo desfibrilador portátil');
        
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
