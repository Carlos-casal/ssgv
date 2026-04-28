<?php

namespace App\Service;

use App\Entity\Material;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ExcelImportService
{
    private EntityManagerInterface $entityManager;
    private ManagerRegistry $managerRegistry;
    private MaterialRepository $materialRepository;
    private MaterialManager $materialManager;
    private string $materialImagesDirectory;
    private array $materialCache = [];
    private array $batchCache = [];

    public function __construct(
        EntityManagerInterface $entityManager,
        ManagerRegistry $managerRegistry,
        MaterialRepository $materialRepository,
        MaterialManager $materialManager,
        string $materialImagesDirectory
    ) {
        $this->entityManager = $entityManager;
        $this->managerRegistry = $managerRegistry;
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
            'networkId' => ['id de red', 'red', 'issi', 'imei', 'network'],
            'phoneNumber' => ['teléfono', 'móvil', 'phone'],
            'purchaseDate' => ['compra', 'purchase'],
            'warrantyDate' => ['garantía', 'fin', 'warranty'],
            'description' => ['descripción', 'notas', 'description'],
            'hasCharger' => ['cargador', 'charger'],
            'hasClip' => ['pinza', 'clip'],
            'hasMicrophone' => ['micro', 'altavoz', 'mic']
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
        $this->materialCache = [];
        $this->batchCache = [];
        $this->countedMaterials = [];
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        
        $map = $this->mapColumns($worksheet);

        $preview = [
            'total_rows' => 0,
            'materials' => [], // Grouped by target Material
            'conflicts' => [], // Serial number conflicts
            'errors' => []
        ];

        $highestRow = $worksheet->getHighestRow();
        $seenSns = [];
        $seenAliases = [];

        for ($row = 2; $row <= $highestRow; $row++) {
            $name = isset($map['name']) ? $this->getCellValue($worksheet, $map['name'], $row) : null;
            if (empty($name)) continue;

            $barcode = isset($map['barcode']) ? $this->getCellValue($worksheet, $map['barcode'], $row) : null;
            $category = isset($map['category']) ? $this->getCellValue($worksheet, $map['category'], $row) : null;
            $nature = isset($map['nature']) ? $this->getCellValue($worksheet, $map['nature'], $row) : null;
            $sn = isset($map['serialNumber']) ? trim((string)$this->getCellValue($worksheet, $map['serialNumber'], $row)) : null;
            if ($sn === '' || $sn === 'S/N') $sn = null;
            $nid = isset($map['networkId']) ? $this->getCellValue($worksheet, $map['networkId'], $row) : null;

            $unitsPerPackage = isset($map['unitsPerPackage']) ? (int)$this->getCellValue($worksheet, $map['unitsPerPackage'], $row) : 1;
            if ($unitsPerPackage <= 0) $unitsPerPackage = 1;
            $numPackages = isset($map['numPackages']) ? (int)$this->getCellValue($worksheet, $map['numPackages'], $row) : 0;
            $stock = $unitsPerPackage * $numPackages;
            
            $preview['total_rows']++;
            
            $material = $this->findExistingMaterial($name, $barcode, $sn, $nid);
            $key = $material ? 'm_' . $material->getId() : 'new_' . $name . '_' . ($barcode ?? '');

            if (!isset($preview['materials'][$key])) {
                $preview['materials'][$key] = [
                    'is_new' => $material === null,
                    'name' => $material ? $material->getName() : $name,
                    'barcode' => $material ? $material->getBarcode() : $barcode,
                    'category' => $material ? $material->getCategory() : $category,
                    'nature' => $material ? $material->getNature() : $nature,
                    'current_stock' => $material ? $material->getStock() : 0,
                    'stock_to_add' => 0,
                    'units_to_create' => 0,
                    'batches_to_create' => [],
                ];
            }

            // Check for Serial Number and Alias conflicts in EQUIPO_TECNICO
            $resolvedNature = $material ? $material->getNature() : $nature;
            $alias = isset($map['alias']) ? $this->getCellValue($worksheet, $map['alias'], $row) : null;

            if ($resolvedNature === Material::NATURE_TECHNICAL) {
                $brandModel = isset($map['brandModel']) ? $this->getCellValue($worksheet, $map['brandModel'], $row) : null;
                $totalPrice = isset($map['totalPrice']) ? $this->getCellValue($worksheet, $map['totalPrice'], $row) : null;

                // 1. Alias Conflict Check
                if ($alias) {
                    $existingByAlias = $this->entityManager->getRepository(\App\Entity\MaterialUnit::class)->findOneBy(['alias' => $alias]);
                    if ($existingByAlias) {
                        $preview['errors'][] = "Fila {$row}: El alias '{$alias}' ya existe en la base de datos.";
                    } elseif (isset($seenAliases[$alias])) {
                        $preview['errors'][] = "Fila {$row}: El alias '{$alias}' está duplicado en el propio Excel.";
                    }
                    $seenAliases[$alias] = true;
                }

                // 2. Serial Number Conflict Check
                if ($sn) {
                    $conflictType = null;
                    $existingUnit = $this->entityManager->getRepository(\App\Entity\MaterialUnit::class)->findOneBy(['serialNumber' => $sn]);

                    if ($existingUnit) {
                        $conflictType = 'database';
                    } elseif (isset($seenSns[$sn])) {
                        $conflictType = 'excel';
                    }

                    if ($conflictType) {
                        $preview['conflicts'][$sn] = [
                            'type' => $conflictType,
                            'serialNumber' => $sn,
                            'existing' => $existingUnit ? [
                                'alias' => $existingUnit->getAlias(),
                                'brandModel' => $existingUnit->getMaterial()->getBrandModel(),
                                'materialName' => $existingUnit->getMaterial()->getName(),
                                'networkId' => $existingUnit->getNetworkId(),
                                'phoneNumber' => $existingUnit->getPhoneNumber(),
                                'price' => $existingUnit->getPurchasePrice(),
                            ] : $seenSns[$sn],
                            'new' => [
                                'alias' => $alias,
                                'brandModel' => $brandModel,
                                'materialName' => $name,
                                'networkId' => $nid,
                                'phoneNumber' => isset($map['phoneNumber']) ? $this->getCellValue($worksheet, $map['phoneNumber'], $row) : null,
                                'price' => $totalPrice,
                            ]
                        ];
                    }

                    $seenSns[$sn] = [
                        'alias' => $alias,
                        'brandModel' => $brandModel,
                        'materialName' => $name,
                        'networkId' => $nid,
                        'phoneNumber' => isset($map['phoneNumber']) ? $this->getCellValue($worksheet, $map['phoneNumber'], $row) : null,
                        'price' => $totalPrice,
                    ];
                }
            }

            $preview['materials'][$key]['stock_to_add'] += $stock;

            // For technical nature, track if we are creating a unit
            if ($resolvedNature === Material::NATURE_TECHNICAL && $sn) {
                // Only count as unit to create if it's NOT a conflict or we'll decide later
                // Actually, let's just count them all for now, the UI will show conflicts separately
                $preview['materials'][$key]['units_to_create']++;
            }

            // For consumable nature, track batches
            if ($resolvedNature !== Material::NATURE_TECHNICAL) {
                $batchNum = $this->getCellValue($worksheet, $map['batchNumber'] ?? null, $row) ?? 'LOTE-EXCEL';
                if (!in_array($batchNum, $preview['materials'][$key]['batches_to_create'])) {
                    $preview['materials'][$key]['batches_to_create'][] = $batchNum;
                }
            }
        }
        
        return $preview;
    }

    private function sanitizeNumeric(?string $value): ?string
    {
        if ($value === null) return null;
        $clean = trim($value);
        if ($clean === '') return null;

        // Normalize decimal separator
        return str_replace(',', '.', $clean);
    }

    private function ensureEntityManagerIsOpen(): void
    {
        if (!$this->entityManager->isOpen()) {
            $this->entityManager = $this->managerRegistry->resetManager();
            // Refresh repository to use the new manager
            $this->materialRepository = $this->entityManager->getRepository(Material::class);
            // Clear internal caches as they might hold detached entities
            $this->materialCache = [];
            $this->batchCache = [];
        }
    }

    /**
     * Process the Excel import and create/update materials
     */
    public function processImport(File $file, array $resolutions = []): array
    {
        $this->materialCache = [];
        $this->batchCache = [];
        $this->countedMaterials = [];
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        
        $map = $this->mapColumns($worksheet);

        $result = [
            'created' => 0,
            'updated' => 0,
            'units_created' => 0,
            'batches_created' => 0,
            'errors' => []
        ];

        // Extract images from Excel
        $images = $this->extractImagesFromWorksheet($worksheet);
        
        $highestRow = $worksheet->getHighestRow();
        $processedSns = [];

        for ($row = 2; $row <= $highestRow; $row++) {
            try {
                $this->ensureEntityManagerIsOpen();

                $name = isset($map['name']) ? $this->getCellValue($worksheet, $map['name'], $row) : null;
                if (empty($name)) continue;

                $barcode = isset($map['barcode']) ? $this->getCellValue($worksheet, $map['barcode'], $row) : null;
                $category = isset($map['category']) ? $this->getCellValue($worksheet, $map['category'], $row) : null;
                $rawNature = isset($map['nature']) ? mb_strtoupper(trim((string)$this->getCellValue($worksheet, $map['nature'], $row))) : null;

                // Standardize nature
                $nature = null;
                if ($rawNature) {
                    if (in_array($rawNature, ['CONSUMIBLE', 'FUNGIBLE', 'CONSUMO', 'BULK'])) {
                        $nature = Material::NATURE_CONSUMABLE;
                    } elseif (in_array($rawNature, ['EQUIPO_TECNICO', 'EQUIPO', 'TECNICO', 'UNITARIO', 'MOCHILA', 'BOTIQUIN'])) {
                        $nature = Material::NATURE_TECHNICAL;
                    } else {
                        $nature = $rawNature;
                    }
                }

                // If nature is still missing, auto-detect from name or category
                if (!$nature) {
                    $lowerName = mb_strtolower($name);
                    $technicalKeywords = ['mochila', 'botiquín', 'botiquin', 'maletín', 'maletin', 'radio', 'walkie', 'equipo'];
                    foreach ($technicalKeywords as $kw) {
                        if (mb_strpos($lowerName, $kw) !== false) {
                            $nature = Material::NATURE_TECHNICAL;
                            break;
                        }
                    }
                }

                $subFamily = isset($map['subFamily']) ? $this->getCellValue($worksheet, $map['subFamily'], $row) : null;

                $unitsPerPackage = isset($map['unitsPerPackage']) ? (int)$this->getCellValue($worksheet, $map['unitsPerPackage'], $row) : 1;
                if ($unitsPerPackage <= 0) $unitsPerPackage = 1;

                $numPackages = isset($map['numPackages']) ? (int)$this->getCellValue($worksheet, $map['numPackages'], $row) : 0;

                $safetyStock = isset($map['safetyStock']) ? (int)$this->getCellValue($worksheet, $map['safetyStock'], $row) : 0;
                $batchNumber = isset($map['batchNumber']) ? $this->getCellValue($worksheet, $map['batchNumber'], $row) : null;
                $expirationDate = isset($map['expirationDate']) ? $this->getDateValue($worksheet, $map['expirationDate'], $row) : null;
                $supplier = isset($map['supplier']) ? $this->getCellValue($worksheet, $map['supplier'], $row) : null;

                $totalPrice = isset($map['totalPrice']) ? $this->sanitizeNumeric($this->getCellValue($worksheet, $map['totalPrice'], $row)) : null;
                $marginPct = isset($map['marginPct']) ? $this->sanitizeNumeric($this->getCellValue($worksheet, $map['marginPct'], $row)) : null;
                $iva = isset($map['iva']) ? $this->sanitizeNumeric($this->getCellValue($worksheet, $map['iva'], $row)) : null;

                $brandModel = isset($map['brandModel']) ? $this->getCellValue($worksheet, $map['brandModel'], $row) : null;
                $alias = isset($map['alias']) ? $this->getCellValue($worksheet, $map['alias'], $row) : null;
                $serialNumber = isset($map['serialNumber']) ? $this->getCellValue($worksheet, $map['serialNumber'], $row) : null;
                $networkId = isset($map['networkId']) ? $this->getCellValue($worksheet, $map['networkId'], $row) : null;
                $phoneNumber = isset($map['phoneNumber']) ? $this->getCellValue($worksheet, $map['phoneNumber'], $row) : null;
                $purchaseDate = isset($map['purchaseDate']) ? $this->getDateValue($worksheet, $map['purchaseDate'], $row) : null;
                $warrantyDate = isset($map['warrantyDate']) ? $this->getDateValue($worksheet, $map['warrantyDate'], $row) : null;
                $description = isset($map['description']) ? $this->getCellValue($worksheet, $map['description'], $row) : null;

                // Find or Create Material
                $material = $this->findExistingMaterial($name, $barcode, $serialNumber, $networkId);
                
                $isNew = false;
                if (!$material) {
                    $material = new Material();
                    $material->setName($name);
                    $this->entityManager->persist($material);
                    $this->entityManager->flush(); // Necesario para que tenga ID antes de que MaterialManager haga consultas
                    $isNew = true;
                    $result['created']++;
                    $this->addToCache($material);
                } else {
                    // Avoid double-counting "updated" for same material in different rows
                    // We only count it as updated once per session
                    if (!$this->isAlreadyCounted($material)) {
                        $result['updated']++;
                    }
                }

                // Update Material fields
                if ($nature) $material->setNature($nature);
                if ($category) $material->setCategory($category);
                if ($subFamily) $material->setSubFamily($subFamily);
                if ($barcode && $barcode !== 'S/N') $material->setBarcode($barcode);
                if ($serialNumber && $serialNumber !== 'S/N') $material->setSerialNumber($serialNumber);
                if ($safetyStock) $material->setSafetyStock($safetyStock);
                if ($batchNumber) $material->setBatchNumber($batchNumber);
                if ($expirationDate) {
                    $material->setExpirationDate(\DateTime::createFromImmutable($expirationDate));
                }
                if ($supplier) $material->setSupplier($supplier);
                if ($unitsPerPackage) $material->setUnitsPerPackage($unitsPerPackage);
                if ($totalPrice) $material->setTotalPrice($totalPrice);
                if ($marginPct) $material->setMarginPercentage($marginPct);
                if ($iva) $material->setIva($iva);
                if ($brandModel) $material->setBrandModel($brandModel);
                if ($networkId && $networkId !== 'S/N') $material->setNetworkId($networkId);
                if ($phoneNumber) $material->setPhoneNumber($phoneNumber);
                if ($purchaseDate) {
                    $material->setPurchaseDate(\DateTime::createFromImmutable($purchaseDate));
                }
                if ($warrantyDate) {
                    $material->setWarrantyDate(\DateTime::createFromImmutable($warrantyDate));
                }
                if ($description) $material->setDescription($description);

                // Handle Image
                if (isset($images[$row])) {
                    $imagePath = $this->saveImage($images[$row]);
                    $material->setImagePath($imagePath);
                }

                // Handle Stock, Batches and Units
                if ($material->getNature() === Material::NATURE_TECHNICAL) {
                    $cleanSn = $serialNumber ? trim((string)$serialNumber) : null;
                    if ($cleanSn === '' || $cleanSn === 'S/N') $cleanSn = null;

                    if ($cleanSn) {
                        // Search for existing unit with same S/N + Dates
                        $unitRepo = $this->entityManager->getRepository(\App\Entity\MaterialUnit::class);

                        // User requirement: Treat same S/N with different dates as separate entities
                        $criteria = ['serialNumber' => $cleanSn];
                        if ($purchaseDate) $criteria['purchaseDate'] = $purchaseDate;
                        if ($warrantyDate) $criteria['warrantyDate'] = $warrantyDate;

                        $unit = $unitRepo->findOneBy($criteria);

                        // If not found by full criteria, but found by S/N only,
                        // it might be a different "lot" or a duplicate SN that should be blocked if alias matches.
                        if (!$unit && $alias) {
                            $existingByAlias = $unitRepo->findOneBy(['alias' => $alias]);
                            if ($existingByAlias) {
                                $result['errors'][] = "Fila {$row}: El alias '{$alias}' ya está en uso.";
                                continue;
                            }
                        }

                        if (!$unit) {
                            $newUnit = $this->materialManager->createUnit($material, [
                                'serialNumber' => $cleanSn,
                                'alias' => $alias,
                                'brandModel' => $brandModel,
                                'purchasePrice' => $totalPrice,
                                'discountPct' => $marginPct,
                                'networkId' => $networkId,
                                'phoneNumber' => $phoneNumber,
                                'batteryStatus' => '100%',
                                'purchaseDate' => $purchaseDate ? \DateTime::createFromImmutable($purchaseDate) : null,
                                'warrantyDate' => $warrantyDate ? \DateTime::createFromImmutable($warrantyDate) : null,
                                'hasCharger' => (bool)(isset($map['hasCharger']) ? $this->getCellValue($worksheet, $map['hasCharger'], $row) : false),
                                'hasClip' => (bool)(isset($map['hasClip']) ? $this->getCellValue($worksheet, $map['hasClip'], $row) : false),
                                'hasMicrophone' => (bool)(isset($map['hasMicrophone']) ? $this->getCellValue($worksheet, $map['hasMicrophone'], $row) : false),
                            ]);

                            $result['units_created']++;
                        } else {
                            // Update existing unit found by S/N + Dates
                            if ($alias) $unit->setAlias($alias);
                            if ($networkId && $networkId !== 'S/N') $unit->setNetworkId($networkId);
                            if ($phoneNumber) $unit->setPhoneNumber($phoneNumber);
                            if ($totalPrice) $unit->setPurchasePrice($totalPrice);
                            if ($marginPct) $unit->setDiscountPct($marginPct);
                        if ($purchaseDate) {
                            $unit->setPurchaseDate(\DateTime::createFromImmutable($purchaseDate));
                        }
                        if ($warrantyDate) {
                            $unit->setWarrantyDate(\DateTime::createFromImmutable($warrantyDate));
                        }
                            // Ensure unit is linked to current material if it changed
                            $unit->setMaterial($material);

                            // If numPackages > 0 and we found ONE unit, it might mean we are adding more identical units?
                            // But technical units are usually 1 per row.
                            // If the user put numPackages > 1 in a row with a serial number, it's ambiguous.
                            // We'll assume 1 row = 1 unit for technical equipment with S/N.
                        }
                    } else {
                        // Technical bulk stock
                        $this->materialManager->adjustStock($material, $unitsPerPackage * $numPackages, 'Entrada: Registro Inicial / Carga Masiva', $this->materialManager->getDefaultLocation($material));
                    }
                } else {
                    // Consumable - Create or Update Batch
                    $batchNumberValue = $batchNumber ?? 'LOTE-EXCEL';
                    $batch = $this->findExistingBatch($material, $batchNumberValue, $totalPrice, $marginPct, $iva, $unitsPerPackage);

                    if (!$batch) {
                        $batch = new \App\Entity\MaterialBatch();
                        $batch->setMaterial($material);
                        $batch->setBatchNumber($batchNumberValue);
                        $this->entityManager->persist($batch);
                        $this->entityManager->flush(); // Necesario para que tenga ID
                        $this->addToBatchCache($batch);
                        $result['batches_created']++;
                    }

                    if ($expirationDate) $batch->setExpirationDate($expirationDate);
                    if ($supplier) $batch->setSupplier($supplier);
                    $batch->setUnitsPerPackage($unitsPerPackage);
                    $batch->setNumPackages($batch->getNumPackages() + $numPackages);

                    if ($totalPrice) $batch->setTotalPrice($totalPrice);
                    if ($marginPct) $batch->setMarginPercentage($marginPct);
                    $batch->setIva($iva ?? $material->getIva());

                    $totalStockInBatch = $batch->getUnitsPerPackage() * $batch->getNumPackages();
                    if ($totalStockInBatch > 0 && $batch->getTotalPrice()) {
                        $priceVal = (float)$batch->getTotalPrice();
                        if ($batch->getMarginPercentage()) {
                            $priceVal = $priceVal - ($priceVal * ((float)$batch->getMarginPercentage() / 100));
                        }
                        $batch->setUnitPrice((string)($priceVal / $totalStockInBatch));
                    }

                    $this->materialManager->adjustStock(
                        $material,
                        $unitsPerPackage * $numPackages,
                        'Entrada: Registro Inicial / Carga Masiva',
                        $this->materialManager->getDefaultLocation($material),
                        null,
                        $batch
                    );
                }

                $this->entityManager->flush();

            } catch (\Exception $e) {
                $result['errors'][] = "Fila {$row}: " . $e->getMessage();
                // Optionally clear the EM to discard half-applied changes from this row
                if ($this->entityManager->isOpen()) {
                    $this->entityManager->clear();
                    // Refresh repository to use the current manager
                    $this->materialRepository = $this->entityManager->getRepository(Material::class);
                    // Clear internal caches as they might hold detached entities after clear()
                    $this->materialCache = [];
                    $this->batchCache = [];
                }
            }
        }
        
        return $result;
    }

    private array $countedMaterials = [];
    private function isAlreadyCounted(Material $material): bool
    {
        if (in_array($material, $this->countedMaterials, true)) {
            return true;
        }
        $this->countedMaterials[] = $material;
        return false;
    }

    /**
     * Finds a master material record.
     * Prioritizes Barcode/EAN, then Name.
     * Crucially: Serial Number is for identifying the UNIT, not the Material.
     */
    private function findExistingMaterial(?string $name, ?string $barcode, ?string $serialNumber, ?string $networkId): ?Material
    {
        $barcode = !empty(trim($barcode)) && trim($barcode) !== 'S/N' ? trim($barcode) : null;
        $name = !empty(trim($name)) ? trim($name) : null;

        // 1. Check cache first
        foreach ($this->materialCache as $m) {
            if ($barcode && $m->getBarcode() === $barcode) return $m;
            // Only by name if no barcode
            if (!$barcode && $name && $m->getName() === $name) return $m;
        }

        // 2. Check DB
        $material = null;
        if ($barcode) {
            $material = $this->materialRepository->findOneBy(['barcode' => $barcode]);
        }

        if (!$material && $name) {
            // Find by name, but check category too if multiple products share a name
            $material = $this->materialRepository->findOneBy(['name' => $name]);
        }

        if ($material) {
            $this->addToCache($material);
        }

        return $material;
    }

    private function addToCache(Material $material): void
    {
        if (!in_array($material, $this->materialCache, true)) {
            $this->materialCache[] = $material;
        }
    }

    private function findExistingBatch(Material $material, string $batchNumber, ?string $totalPrice, ?string $marginPct, ?string $iva, int $unitsPerPackage): ?\App\Entity\MaterialBatch
    {
        // 1. Check cache
        foreach ($this->batchCache as $b) {
            if ($b->getMaterial() === $material &&
                $b->getBatchNumber() === $batchNumber &&
                (string)$b->getTotalPrice() === (string)$totalPrice &&
                (string)$b->getMarginPercentage() === (string)$marginPct &&
                (string)$b->getIva() === (string)($iva ?? $material->getIva()) &&
                $b->getUnitsPerPackage() === $unitsPerPackage
            ) {
                return $b;
            }
        }

        // 2. Check DB
        $batch = $this->entityManager->getRepository(\App\Entity\MaterialBatch::class)->findOneBy([
            'material' => $material,
            'batchNumber' => $batchNumber,
            'totalPrice' => $totalPrice,
            'marginPercentage' => $marginPct,
            'iva' => $iva ?? $material->getIva(),
            'unitsPerPackage' => $unitsPerPackage
        ]);

        if ($batch) {
            $this->addToBatchCache($batch);
        }

        return $batch;
    }

    private function addToBatchCache(\App\Entity\MaterialBatch $batch): void
    {
        if (!in_array($batch, $this->batchCache, true)) {
            $this->batchCache[] = $batch;
        }
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

            // For numbers that might be scientific notation in raw value, formatted value is often better for barcodes
            if (is_numeric($value) && (strpos((string)$value, 'E+') !== false || strlen((string)$value) > 10)) {
                $value = $cell->getFormattedValue();
            }
        }

        $result = is_string($value) ? trim($value) : (string)$value;
        
        // Ensure UTF-8 encoding to prevent ?? characters
        if (!empty($result) && !mb_check_encoding($result, 'UTF-8')) {
            $result = mb_convert_encoding($result, 'UTF-8', 'ISO-8859-1');
        }

        return $result;
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
            'U1' => 'Garantía (DD/MM/AA) *',
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
