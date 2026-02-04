<?php

namespace App\Service;

use App\Entity\Material;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ExcelImportService
{
    private EntityManagerInterface $entityManager;
    private MaterialRepository $materialRepository;
    private string $materialImagesDirectory;

    public function __construct(
        EntityManagerInterface $entityManager,
        MaterialRepository $materialRepository,
        string $materialImagesDirectory
    ) {
        $this->entityManager = $entityManager;
        $this->materialRepository = $materialRepository;
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
            $barcode = $worksheet->getCell("B{$row}")->getValue();
            $category = $worksheet->getCell("C{$row}")->getValue();
            $stock = (int)$worksheet->getCell("D{$row}")->getValue();
            
            // Skip empty rows
            if (empty($name)) {
                continue;
            }
            
            $preview['total_rows']++;
            
            // Check if material exists by barcode
            $existingMaterial = null;
            if ($barcode) {
                $existingMaterial = $this->materialRepository->findOneBy(['barcode' => $barcode]);
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
                $name = $worksheet->getCell("A{$row}")->getValue();
                $barcode = $worksheet->getCell("B{$row}")->getValue();
                $category = $worksheet->getCell("C{$row}")->getValue();
                $stock = (int)$worksheet->getCell("D{$row}")->getValue();
                $description = $worksheet->getCell("E{$row}")->getValue();
                
                // Skip empty rows
                if (empty($name)) {
                    continue;
                }
                
                // Check if material exists by barcode
                $material = null;
                if ($barcode) {
                    $material = $this->materialRepository->findOneBy(['barcode' => $barcode]);
                }
                
                if ($material) {
                    // Update existing material - add to stock
                    $material->setStock($material->getStock() + $stock);
                    $result['updated']++;
                } else {
                    // Create new material
                    $material = new Material();
                    $material->setName($name);
                    $material->setBarcode($barcode);
                    $material->setCategory($category ?? 'Varios');
                    $material->setStock($stock);
                    $material->setSafetyStock(0);
                    $material->setDescription($description);
                    
                    // Check if there's an image for this row
                    if (isset($images[$row])) {
                        $imagePath = $this->saveImage($images[$row]);
                        $material->setImagePath($imagePath);
                    }
                    
                    $this->entityManager->persist($material);
                    $result['created']++;
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
        $sheet->setCellValue('A1', 'Nombre *');
        $sheet->setCellValue('B1', 'Código de Barras');
        $sheet->setCellValue('C1', 'Categoría');
        $sheet->setCellValue('D1', 'Stock Inicial');
        $sheet->setCellValue('E1', 'Descripción');
        
        // Style headers
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']]
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);
        
        // Add example data
        $sheet->setCellValue('A2', 'Linterna LED');
        $sheet->setCellValue('B2', '8435123456789');
        $sheet->setCellValue('C2', 'Logística');
        $sheet->setCellValue('D2', '10');
        $sheet->setCellValue('E2', 'Linterna LED recargable de alta potencia');
        
        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Save to temp file
        $tempFile = tempnam(sys_get_temp_dir(), 'material_template_') . '.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFile);
        
        return $tempFile;
    }
}
