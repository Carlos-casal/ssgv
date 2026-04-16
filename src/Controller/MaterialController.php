<?php

namespace App\Controller;

use App\Entity\Material;
use App\Entity\MaterialUnit;
use App\Entity\MaterialUnitHistory;
use App\Form\MaterialType;
use App\Form\MaterialUnitType;
use App\Form\MaterialUnitStatusType;
use App\Form\MaterialTransferType;
use App\Repository\MaterialRepository;
use App\Repository\MaterialUnitRepository;
use App\Repository\MaterialStockRepository;
use App\Repository\MaterialMovementRepository;
use App\Service\MaterialManager;
use App\Service\ExcelImportService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/material')]
class MaterialController extends AbstractController
{
    #[Route('/', name: 'app_material_index', methods: ['GET'])]
    public function index(Request $request, MaterialRepository $materialRepository, MaterialStockRepository $stockRepository): Response
    {
        $category = $request->query->get('category');
        $subFamily = $request->query->get('subFamily');

        $qb = $materialRepository->createQueryBuilder('m');

        if ($category) {
            $qb->andWhere('m.category = :category')
                ->setParameter('category', $category);
        }

        if ($subFamily) {
            $qb->andWhere('m.subFamily = :subFamily')
                ->setParameter('subFamily', $subFamily);
        }

        $materials = $qb->getQuery()->getResult();

        $response = $this->render('material/index.html.twig', [
            'materials' => $materials,
            'current_category' => $category,
            'current_subfamily' => $subFamily,
            'current_section' => 'recursos'
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;

    }

    #[Route('/new', name: 'app_material_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MaterialManager $materialManager): Response
    {
        $material = new Material();

        // Pre-fill category if provided in URL
        $category = $request->query->get('category');
        if ($category) {
            $material->setCategory($category);

            // Auto-set nature for technical categories
            $technicalCategories = ['Comunicaciones', 'Vehículos', 'Mar', 'Logística'];
            if (in_array($category, $technicalCategories)) {
                $material->setNature(Material::NATURE_TECHNICAL);
            }
        }

        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // DE-DUPLICATION LOGIC:
            // Check if a material with the same name and barcode already exists
            // This prevents creating a new master record if it's already in the database
            $existingMaterial = null;
            if ($material->getBarcode()) {
                $existingMaterial = $entityManager->getRepository(Material::class)->findOneBy([
                    'barcode' => $material->getBarcode(),
                    'category' => $material->getCategory() // Ensure same category to allow duplicate barcodes in different families if needed (unlikely, but safer)
                ]);
            }

            // Only search by name if barcode didn't provide a unique hit
            if (!$existingMaterial) {
                // If the user entered a name that looks like a Kit Alias (Mochila SVB XX), we should be careful
                // For now, only match EXACT master material names in the same category
                $existingMaterial = $entityManager->getRepository(Material::class)->findOneBy([
                    'name' => $material->getName(),
                    'category' => $material->getCategory()
                ]);
            }

            if ($existingMaterial && $existingMaterial->getId() !== $material->getId()) {
                // We found a DIFFERENT material with the same name/barcode.
                // We switch to the existing one to avoid duplicates.
                $material = $existingMaterial;
            }

            // Handle image upload
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('material_images_directory'),
                        $newFilename
                    );
                    $material->setImagePath($newFilename);
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
            }

            $entityManager->persist($material);

            // Handle dynamic batch creation for Consumables
            if ($request->request->has('batches_data') && $material->getNature() === Material::NATURE_CONSUMABLE) {
                $batchesData = $request->request->all('batches_data');
                foreach ($batchesData as $bData) {
                    $batchNumber = $bData['batchNumber'] ?? 'LOTE';
                    $unitsPerPackage = isset($bData['unitsPerPackage']) ? (int)str_replace('.', '', $bData['unitsPerPackage']) : 1;
                    $numPackagesInRow = (int)str_replace('.', '', $bData['numPackages'] ?? 0);
                    $totalPrice = isset($bData['totalPrice']) ? str_replace(',', '.', str_replace('.', '', $bData['totalPrice'])) : null;
                    $marginPercentage = isset($bData['marginPercentage']) ? str_replace(',', '.', str_replace('.', '', $bData['marginPercentage'])) : null;
                    $iva = isset($bData['iva']) ? (string)str_replace('.', '', $bData['iva']) : (string)$material->getIva();

                    // Check for existing batch for consolidation in NEW action
                    $batch = $entityManager->getRepository(\App\Entity\MaterialBatch::class)->findOneBy([
                        'material' => $material,
                        'batchNumber' => $batchNumber,
                        'unitsPerPackage' => $unitsPerPackage,
                        'totalPrice' => $totalPrice,
                        'marginPercentage' => $marginPercentage,
                        'iva' => $iva
                    ]);

                    if (!$batch) {
                        $batch = new \App\Entity\MaterialBatch();
                        $batch->setMaterial($material);
                        $entityManager->persist($batch);
                    }

                    $batch->setBatchNumber($batchNumber);
                    if (!empty($bData['expirationDate'])) {
                        $batch->setExpirationDate(new \DateTimeImmutable($bData['expirationDate']));
                    }
                    $batch->setSupplier($bData['supplier'] ?? null);
                    $batch->setUnitsPerPackage($unitsPerPackage);
                    $batch->setNumPackages($batch->getNumPackages() + $numPackagesInRow);
                    $batch->setTotalPrice($totalPrice);
                    $batch->setMarginPercentage($marginPercentage);
                    $batch->setIva((int)$iva);

                    // Calculate unit price for storage
                    $currentTotalStock = $batch->getUnitsPerPackage() * $batch->getNumPackages();
                    if ($currentTotalStock > 0 && $batch->getTotalPrice()) {
                        $price = (float)$batch->getTotalPrice();
                        if ($batch->getMarginPercentage()) {
                            $price = $price - ($price * ((float)$batch->getMarginPercentage() / 100));
                        }
                        $batch->setUnitPrice((string)($price / $currentTotalStock));
                    }

                    $entityManager->persist($batch);

                    // Initialize stock for this batch in Central Warehouse
                    $addedStock = $unitsPerPackage * $numPackagesInRow;
                    if ($addedStock > 0) {
                        $materialManager->adjustStock($material, $addedStock, 'Entrada: Registro Inicial', $materialManager->getCentralWarehouse(), null, $batch);
                    }
                }
            }

            // Handle dynamic unit creation for Communications or Technical Equipment
            if ($request->request->has('units_data')) {
                $unitsData = $request->request->all('units_data');

                // Validate Serial Numbers for uniqueness
                $seenSNs = [];
                foreach ($unitsData as $unitData) {
                    if (!empty($unitData['serialNumber'])) {
                        // Check if duplicate within the submission
                        if (in_array($unitData['serialNumber'], $seenSNs)) {
                            $this->addFlash('error', 'Has introducido el número de serie ' . $unitData['serialNumber'] . ' varias veces.');
                            return $this->render('material/new.html.twig', [
                                'material' => $material,
                                'form' => $form,
                                'units_data' => $unitsData,
                                'current_section' => 'recursos'
                            ]);
                        }
                        $seenSNs[] = $unitData['serialNumber'];

                        // Check if exists in DB
                        $existing = $entityManager->getRepository(MaterialUnit::class)->findOneBy(['serialNumber' => $unitData['serialNumber']]);
                        if ($existing) {
                            $this->addFlash('error', 'El número de serie ' . $unitData['serialNumber'] . ' ya está registrado en otra unidad.');
                            return $this->render('material/new.html.twig', [
                                'material' => $material,
                                'form' => $form,
                                'units_data' => $unitsData,
                                'current_section' => 'recursos'
                            ]);
                        }
                    }
                }

                // Extract common data from the first unit for Technical Equipment
                if ($material->getNature() === Material::NATURE_TECHNICAL && !empty($unitsData)) {
                    $first = $unitsData[0];
                    if (isset($first['brandModel'])) $material->setBrandModel($first['brandModel']);
                    if (!empty($first['purchaseDate'])) {
                        $material->setPurchaseDate(new \DateTimeImmutable($first['purchaseDate']));
                    }
                    if (!empty($first['warrantyEndDate'])) {
                        $material->setWarrantyEndDate(new \DateTimeImmutable($first['warrantyEndDate']));
                    }
                }

                foreach ($unitsData as $unitData) {
                    $materialManager->createUnit($material, [
                        'alias' => $unitData['alias'] ?? null,
                        'serialNumber' => $unitData['serialNumber'] ?? null,
                        'networkId' => $unitData['networkId'] ?? null,
                        'phoneNumber' => $unitData['phoneNumber'] ?? null,
                        'batteryStatus' => $unitData['batteryStatus'] ?? null,
                        'hasCharger' => $form->get('hasCharger')->getData(), // Apply from bulk selection
                        'hasClip' => $form->get('hasClip')->getData(),
                        'purchasePrice' => isset($unitData['purchasePrice']) ? str_replace(',', '.', $unitData['purchasePrice']) : null,
                        'discountPct' => isset($unitData['discountPct']) ? str_replace(',', '.', $unitData['discountPct']) : null,
                    ]);
                }
            }

            // Handle initial stock from grid
            if ($request->request->has('initial_stock')) {
                $adjustments = $request->request->all('initial_stock');
                $reason = 'Inicialización de stock';
                foreach ($adjustments as $quantity) {
                    if ($quantity > 0) {
                        $materialManager->adjustStock($material, (int)$quantity, $reason);
                    }
                }
                // Handle custom
                $customQty = (int)$request->request->get('custom_qty');
                if ($customQty > 0) {
                    $materialManager->adjustStock($material, $customQty, $reason);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_material_index', [], Response::HTTP_SEE_OTHER);
        }

        $unitsData = $request->request->all('units_data');
        $batchesData = $request->request->all('batches_data');

        $response = $this->render('material/new.html.twig', [
            'material' => $material,
            'form' => $form,
            'units_data' => $unitsData,
            'batches_data' => $batchesData,
            'current_section' => 'recursos'
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;

    }

    #[Route('/import/template', name: 'app_material_import_template', methods: ['GET'])]
    public function downloadTemplate(ExcelImportService $importService): Response
    {
        $templatePath = $importService->generateTemplate();

        $response = new BinaryFileResponse($templatePath);
        // Explicitly set MIME type to bypass LogicException when fileinfo extension is missing
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'plantilla_materiales.xlsx'
        );

        return $response;
    }

    #[Route('/import/preview', name: 'app_material_import_preview', methods: ['POST'])]
    public function importPreview(Request $request, ExcelImportService $importService): Response
    {
        $file = $request->files->get('import_file');

        if (!$file) {
            $this->addFlash('error', 'Por favor selecciona un archivo Excel.');
            return $this->redirectToRoute('app_material_index');
        }

        try {
            $preview = $importService->previewImport($file);

            // Store file temporarily for later processing
            $tempPath = $this->getParameter('kernel.project_dir') . '/var/tmp/';
            if (!is_dir($tempPath)) {
                mkdir($tempPath, 0777, true);
            }
            $tempFilename = uniqid('import_') . '.xlsx';
            $file->move($tempPath, $tempFilename);

            $response = $this->render('material/import_preview.html.twig', [
                'preview' => $preview,
                'temp_filename' => $tempFilename,
                'current_section' => 'recursos'
            ]);
            $response->headers->set('Content-Type', 'text/html; charset=utf-8');
            return $response;

        } catch (\Exception $e) {
            $this->addFlash('error', 'Error al procesar el archivo: ' . $e->getMessage());
            return $this->redirectToRoute('app_material_index');
        }
    }

    #[Route('/import/process', name: 'app_material_import_process', methods: ['POST'])]
    public function importProcess(Request $request, ExcelImportService $importService): Response
    {
        $tempFilename = $request->request->get('temp_filename');
        $tempPath = $this->getParameter('kernel.project_dir') . '/var/tmp/' . $tempFilename;

        if (!file_exists($tempPath)) {
            $this->addFlash('error', 'Archivo temporal no encontrado. Por favor intenta de nuevo.');
            return $this->redirectToRoute('app_material_index');
        }

        try {
            $file = new \Symfony\Component\HttpFoundation\File\File($tempPath);
            $resolutions = $request->request->all('conflict_resolutions');
            $result = $importService->processImport($file, $resolutions);

            // Delete temp file
            unlink($tempPath);

            $message = sprintf(
                'Importación completada: %d creados, %d actualizados. ',
                $result['created'],
                $result['updated']
            );

            if ($result['units_created'] > 0 || $result['batches_created'] > 0) {
                $details = [];
                if ($result['units_created'] > 0) $details[] = $result['units_created'] . ' unidades técnicas';
                if ($result['batches_created'] > 0) $details[] = $result['batches_created'] . ' lotes nuevos';
                $message .= '(' . implode(', ', $details) . ')';
            }

            if (!empty($result['errors'])) {
                $message .= ' Errores: ' . implode(', ', $result['errors']);
                $this->addFlash('warning', $message);
            } else {
                $this->addFlash('success', $message);
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error al importar: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_material_index');
    }

    #[Route('/check-barcode', name: 'app_material_check_barcode', methods: ['GET'])]
    public function checkBarcode(Request $request, MaterialRepository $materialRepository): JsonResponse
    {
        $barcode = $request->query->get('barcode');
        $excludeId = $request->query->get('excludeId');

        if (!$barcode) {
            return new JsonResponse(['exists' => false]);
        }

        $material = $materialRepository->findOneBy(['barcode' => $barcode]);

        $exists = false;
        $name = null;
        $id = null;
        if ($material) {
            if (!$excludeId || $material->getId() !== (int)$excludeId) {
                $exists = true;
                $name = $material->getName();
                $id = $material->getId();
            }
        }

        return new JsonResponse([
            'exists' => $exists,
            'name' => $name,
            'id' => $id
        ]);
    }

    #[Route('/check-serial-number', name: 'app_material_check_serial_number', methods: ['GET'])]
    public function checkSerialNumber(Request $request, MaterialRepository $materialRepository, MaterialUnitRepository $unitRepository): JsonResponse
    {
        $serialNumber = $request->query->get('serialNumber');
        $excludeMaterialId = $request->query->get('excludeMaterialId');

        if (!$serialNumber) {
            return new JsonResponse(['exists' => false]);
        }

        $exists = false;

        // Check in global Material level
        $material = $materialRepository->findOneBy(['serialNumber' => $serialNumber]);
        if ($material && (!$excludeMaterialId || $material->getId() !== (int)$excludeMaterialId)) {
            $exists = true;
        }

        // Check in MaterialUnit level
        if (!$exists) {
            $unit = $unitRepository->findOneBy(['serialNumber' => $serialNumber]);
            // Currently not excluding unit id, as the form usually checks global material or new units
            // For new units they won't have an ID yet
            if ($unit) {
                $exists = true;
            }
        }

        return new JsonResponse(['exists' => $exists]);
    }

    #[Route('/{id}', name: 'app_material_show', methods: ['GET'])]
    public function show(Request $request, Material $material, MaterialMovementRepository $movementRepository, \Knp\Component\Pager\PaginatorInterface $paginator): Response
    {
        $queryBuilder = $movementRepository->createQueryBuilder('m')
            ->where('m.material = :material')
            ->setParameter('material', $material)
            ->orderBy('m.createdAt', 'DESC');

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5
        );

        $response = $this->render('material/show.html.twig', [
            'material' => $material,
            'pagination' => $pagination,
            'current_section' => 'recursos'
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;

    }

    #[Route('/{id}/edit', name: 'app_material_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Material $material, EntityManagerInterface $entityManager, MaterialManager $materialManager): Response
    {
        $form = $this->createForm(MaterialType::class, $material);

        if (!$request->isMethod('POST')) {
            // Populate unmapped fields for Block D
            if ($material->getUnitsPerPackage() > 0) {
                $numPacks = floor($material->getStock() / $material->getUnitsPerPackage());
            } else {
                $numPacks = $material->getStock();
            }
            $form->get('numPackages')->setData($numPacks);

            $totalPrice = (float)$material->getTotalPrice();
            $discount = (float)$material->getDiscountPercentage();
            $discounted = $totalPrice - ($totalPrice * ($discount / 100));
            $form->get('discountedPrice')->setData($discounted);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle image upload
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('material_images_directory'),
                        $newFilename
                    );

                    // Delete old image if exists
                    if ($material->getImagePath()) {
                        $oldImagePath = $this->getParameter('material_images_directory') . '/' . $material->getImagePath();
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }

                    $material->setImagePath($newFilename);
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
            }

            // $entityManager->flush(); // Removed redundant flush

            // Handle dynamic batch creation/update in edit
            if ($request->request->has('batches_data') && $material->getNature() === Material::NATURE_CONSUMABLE) {
                $batchesData = $request->request->all('batches_data');
                $submittedBatchIds = array_filter(array_column($batchesData, 'id'));

                // Delete batches that are not in the submitted data
                foreach ($material->getBatches() as $existingBatch) {
                    if (!in_array($existingBatch->getId(), $submittedBatchIds)) {
                        $entityManager->remove($existingBatch);
                    }
                }

                foreach ($batchesData as $bData) {
                    $batchNumber = $bData['batchNumber'] ?? 'LOTE';
                    $unitsPerPackage = isset($bData['unitsPerPackage']) ? (int)str_replace('.', '', $bData['unitsPerPackage']) : 1;
                    $totalPrice = isset($bData['totalPrice']) ? str_replace(',', '.', str_replace('.', '', $bData['totalPrice'])) : null;
                    $marginPercentage = isset($bData['marginPercentage']) ? str_replace(',', '.', str_replace('.', '', $bData['marginPercentage'])) : null;
                    $iva = isset($bData['iva']) ? (string)str_replace('.', '', $bData['iva']) : (string)$material->getIva();

                    $batch = null;
                    $isNewRowInForm = empty($bData['id']);
                    if (!$isNewRowInForm) {
                        $batch = $entityManager->getRepository(\App\Entity\MaterialBatch::class)->find($bData['id']);
                    }

                    if (!$batch) {
                        // Check for existing batch with same properties for consolidation
                        $batch = $entityManager->getRepository(\App\Entity\MaterialBatch::class)->findOneBy([
                            'material' => $material,
                            'batchNumber' => $batchNumber,
                            'unitsPerPackage' => $unitsPerPackage,
                            'totalPrice' => $totalPrice,
                            'marginPercentage' => $marginPercentage,
                            'iva' => $iva
                        ]);
                    }

                    if (!$batch) {
                        $batch = new \App\Entity\MaterialBatch();
                        $batch->setMaterial($material);
                        $entityManager->persist($batch);
                    }

                    $batch->setBatchNumber($batchNumber);
                    if (!empty($bData['expirationDate'])) {
                        $batch->setExpirationDate(new \DateTimeImmutable($bData['expirationDate']));
                    }
                    $batch->setSupplier($bData['supplier'] ?? null);

                    $oldTotalStock = $batch->getUnitsPerPackage() * $batch->getNumPackages();

                    $batch->setUnitsPerPackage($unitsPerPackage);
                    if ($isNewRowInForm) {
                        $batch->setNumPackages($batch->getNumPackages() + (int)str_replace('.', '', $bData['numPackages'] ?? 0));
                    } else {
                        $batch->setNumPackages(isset($bData['numPackages']) ? (int)str_replace('.', '', $bData['numPackages']) : 0);
                    }
                    $batch->setTotalPrice($totalPrice);
                    $batch->setMarginPercentage($marginPercentage);
                    $batch->setIva((int)$iva);

                    $newTotalStock = $batch->getUnitsPerPackage() * $batch->getNumPackages();

                    // Calculate unit price
                    if ($newTotalStock > 0 && $batch->getTotalPrice()) {
                        $price = (float)$batch->getTotalPrice();
                        if ($batch->getMarginPercentage()) {
                            $price = $price - ($price * ((float)$batch->getMarginPercentage() / 100));
                        }
                        $batch->setUnitPrice((string)($price / $newTotalStock));
                    }

                    // If stock changed, adjust it in Central Warehouse
                    if ($newTotalStock !== $oldTotalStock) {
                        $diff = $newTotalStock - $oldTotalStock;
                        $materialManager->adjustStock($material, $diff, 'Ajuste manual de stock', $materialManager->getCentralWarehouse(), null, $batch);
                    }
                }
            }

            // Handle dynamic unit creation in edit (only for NEW units)
            if ($request->request->has('units_data')) {
                $unitsData = $request->request->all('units_data');
                $existingUnits = $material->getUnits()->toArray();
                $existingCount = count($existingUnits);

                // Update existing units with submitted data (status, price)
                foreach ($existingUnits as $idx => $unit) {
                    if (isset($unitsData[$idx])) {
                        $uData = $unitsData[$idx];
                        // Track status change and record traceability
                        if (isset($uData['operationalStatus'])) {
                            $newStatus = $uData['operationalStatus'];
                            if ($newStatus !== $unit->getOperationalStatus()) {
                                $materialManager->changeUnitStatus($unit, $newStatus, $uData['statusReason'] ?? null);
                            }
                        }
                        if (isset($uData['purchasePrice'])) {
                            $unit->setPurchasePrice($uData['purchasePrice'] !== '' ? str_replace(',', '.', $uData['purchasePrice']) : null);
                        }
                        if (isset($uData['discountPct'])) {
                            $unit->setDiscountPct($uData['discountPct'] !== '' ? str_replace(',', '.', $uData['discountPct']) : null);
                        }
                    }
                }

                // Validate Serial Numbers for uniqueness (only for NEW units being added)
                if (count($unitsData) > $existingCount) {
                    $seenSNs = [];
                    // Add existing unit SNs to seen list
                    foreach ($material->getUnits() as $unit) {
                        if ($unit->getSerialNumber()) $seenSNs[] = $unit->getSerialNumber();
                    }

                    for ($i = $existingCount; $i < count($unitsData); $i++) {
                        $unitData = $unitsData[$i];
                        if (!empty($unitData['serialNumber'])) {
                            // Check if duplicate within the submission
                            if (in_array($unitData['serialNumber'], $seenSNs)) {
                                $this->addFlash('error', 'El número de serie ' . $unitData['serialNumber'] . ' ya está en uso o repetido.');
                                return $this->render('material/edit.html.twig', [
                                    'material' => $material,
                                    'form' => $form,
                                    'units_data' => $unitsData,
                                    'current_section' => 'recursos'
                                ]);
                            }
                            $seenSNs[] = $unitData['serialNumber'];

                            // Check if exists in DB
                            $existing = $entityManager->getRepository(MaterialUnit::class)->findOneBy(['serialNumber' => $unitData['serialNumber']]);
                            if ($existing) {
                                $this->addFlash('error', 'El número de serie ' . $unitData['serialNumber'] . ' ya está registrado en otra unidad.');
                                return $this->render('material/edit.html.twig', [
                                    'material' => $material,
                                    'form' => $form,
                                    'units_data' => $unitsData,
                                    'current_section' => 'recursos'
                                ]);
                            }
                        }
                    }
                }

                // Update common data for Technical Equipment from the first unit block
                if ($material->getNature() === Material::NATURE_TECHNICAL && !empty($unitsData)) {
                    $first = $unitsData[0];
                    if (isset($first['brandModel'])) $material->setBrandModel($first['brandModel']);
                    if (!empty($first['purchaseDate'])) {
                        $material->setPurchaseDate(new \DateTimeImmutable($first['purchaseDate']));
                    }
                    if (!empty($first['warrantyEndDate'])) {
                        $material->setWarrantyEndDate(new \DateTimeImmutable($first['warrantyEndDate']));
                    }
                    if (isset($first['operationalStatus'])) {
                        $material->setOperationalStatus($first['operationalStatus']);
                    }
                }

                $existingCount = count($material->getUnits());
                if (count($unitsData) > $existingCount) {
                    for ($i = $existingCount; $i < count($unitsData); $i++) {
                        $unitData = $unitsData[$i];
                        $materialManager->createUnit($material, [
                            'alias' => $unitData['alias'] ?? null,
                            'serialNumber' => $unitData['serialNumber'] ?? null,
                            'networkId' => $unitData['networkId'] ?? null,
                            'phoneNumber' => $unitData['phoneNumber'] ?? null,
                            'batteryStatus' => $unitData['batteryStatus'] ?? null,
                            'hasCharger' => $form->get('hasCharger')->getData(),
                            'hasClip' => $form->get('hasClip')->getData(),
                            'purchasePrice' => isset($unitData['purchasePrice']) ? str_replace(',', '.', $unitData['purchasePrice']) : null,
                            'discountPct' => isset($unitData['discountPct']) ? str_replace(',', '.', $unitData['discountPct']) : null,
                        ]);
                    }
                }
            }

            // Handle initial stock from grid (even in edit, it acts as an addition)
            if ($request->request->has('initial_stock')) {
                $adjustments = $request->request->all('initial_stock');
                $reason = 'Ajuste desde edición';
                foreach ($adjustments as $quantity) {
                    if ($quantity > 0) {
                        $materialManager->adjustStock($material, (int)$quantity, $reason);
                    }
                }
                // Handle custom
                $customQty = (int)$request->request->get('custom_qty');
                if ($customQty > 0) {
                    $materialManager->adjustStock($material, $customQty, $reason);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_material_index', [], Response::HTTP_SEE_OTHER);
        }

            // Prepare existing units and batches data for the frontend
        $unitsData = $request->request->all('units_data');
        $batchesData = $request->request->all('batches_data');
        if (empty($unitsData)) {
            $unitsData = [];
            foreach ($material->getUnits() as $unit) {
                $unitsData[] = [
                    'id' => $unit->getId(),
                    'alias' => $unit->getAlias(),
                    'serialNumber' => $unit->getSerialNumber(),
                    'brandModel' => $material->getBrandModel(),
                    'purchaseDate' => $material->getPurchaseDate() ? $material->getPurchaseDate()->format('Y-m-d') : null,
                    'warrantyEndDate' => $material->getWarrantyEndDate() ? $material->getWarrantyEndDate()->format('Y-m-d') : null,
                    'operationalStatus' => $unit->getOperationalStatus(),
                    'batteryStatus' => $unit->getBatteryStatus(),
                    'networkId' => $unit->getNetworkId(),
                    'phoneNumber' => $unit->getPhoneNumber(),
                    'purchasePrice' => $unit->getPurchasePrice(),
                    'discountPct' => $unit->getDiscountPct(),
                    'history' => array_map(fn($h) => [
                        'date'   => $h->getCreatedAt() ? $h->getCreatedAt()->format('d/m/Y H:i') : '-',
                        'status' => $h->getStatus(),
                        'user'   => $h->getUser() ? ($h->getUser()->getName() ?? $h->getUser()->getEmail()) : 'Sistema',
                        'reason' => $h->getReason(),
                    ], $unit->getHistory()->toArray()),
                ];
            }
        }

        if (empty($batchesData)) {
            $batchesData = [];
            foreach ($material->getBatches() as $batch) {
                $batchesData[] = [
                    'id' => $batch->getId(),
                    'batchNumber' => $batch->getBatchNumber(),
                    'expirationDate' => $batch->getExpirationDate() ? $batch->getExpirationDate()->format('Y-m-d') : null,
                    'supplier' => $batch->getSupplier(),
                    'unitsPerPackage' => $batch->getUnitsPerPackage(),
                    'numPackages' => $batch->getNumPackages(),
                    'totalPrice' => $batch->getTotalPrice(),
                    'marginPercentage' => $batch->getMarginPercentage(),
                    'unitPrice' => $batch->getUnitPrice(),
                ];
            }
        }

        $response = $this->render('material/edit.html.twig', [
            'material' => $material,
            'form' => $form,
            'units_data' => $unitsData,
            'batches_data' => $batchesData,
            'current_section' => 'recursos'
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;

    }

    #[Route('/{id}/unit/new', name: 'app_material_unit_new', methods: ['GET', 'POST'])]
    public function newUnit(Request $request, Material $material, EntityManagerInterface $entityManager, MaterialManager $materialManager): Response
    {
        $unit = new MaterialUnit();
        $unit->setMaterial($material);
        $form = $this->createForm(MaterialUnitType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $materialManager->createUnit($material, [
                'collectiveNumber' => $unit->getCollectiveNumber(),
                'serialNumber' => $unit->getSerialNumber(),
                'pttStatus' => $unit->getPttStatus(),
                'coverStatus' => $unit->getCoverStatus(),
                'batteryStatus' => $unit->getBatteryStatus(),
            ], $unit->getLocation());

            $entityManager->flush();

            return $this->redirectToRoute('app_material_show', ['id' => $material->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('material/unit_new.html.twig', [
            'material' => $material,
            'unit' => $unit,
            'form' => $form,
            'current_section' => 'recursos'
        ]);
    }

    #[Route('/{id}/unit/bulk', name: 'app_material_unit_bulk', methods: ['GET', 'POST'])]
    public function bulkAddUnits(Request $request, Material $material, EntityManagerInterface $entityManager, MaterialManager $materialManager): Response
    {
        if ($request->isMethod('POST')) {
            $unitsData = $request->request->all('units');
            foreach ($unitsData as $data) {
                $materialManager->createUnit($material, $data);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_material_show', ['id' => $material->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('material/bulk_unit_new.html.twig', [
            'material' => $material,
            'current_section' => 'recursos'
        ]);
    }

    #[Route('/{id}/transfer', name: 'app_material_transfer', methods: ['GET', 'POST'])]
    public function transfer(Request $request, Material $material, MaterialManager $materialManager, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaterialTransferType::class, null, ['material' => $material]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $materialManager->transfer(
                $material,
                $data['origin'],
                $data['destination'],
                $data['quantity'],
                $data['reason'],
                $data['responsible'],
                $data['materialUnit'] ?? null
            );

            $entityManager->flush();

            $this->addFlash('success', 'Movimiento registrado correctamente.');

            return $this->redirectToRoute('app_material_show', ['id' => $material->getId()]);
        }

        return $this->render('material/transfer.html.twig', [
            'material' => $material,
            'form' => $form,
            'current_section' => 'recursos'
        ]);
    }

    #[Route('/{id}/stock/adjust', name: 'app_material_stock_adjust', methods: ['POST'])]
    public function adjustStock(Request $request, Material $material, MaterialManager $materialManager, EntityManagerInterface $entityManager): Response
    {
        $reason = $request->request->get('reason', 'Ajuste manual');

        // 1. Bulk adjustments from standard grid
        $adjustments = $request->request->all('adjustments');
        if (!empty($adjustments)) {
            $materialManager->bulkAdjustStock($material, $adjustments, $reason);
        }

        // 2. Manual entry from custom column
        $customQty = (int)$request->request->get('custom_qty');
        if ($customQty !== 0) {
            $materialManager->adjustStock($material, $customQty, $reason);
        }

        // 3. Individual adjustments (from old logic or API-like single calls)
        $quantity = (int)$request->request->get('quantity');
        if ($quantity !== 0 && empty($adjustments)) {
            $materialManager->adjustStock($material, $quantity, $reason);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_material_show', ['id' => $material->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/unit/{id}/edit', name: 'app_material_unit_edit', methods: ['GET', 'POST'])]
    public function editUnit(Request $request, MaterialUnit $unit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaterialUnitType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_material_show', ['id' => $unit->getMaterial()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('material/unit_edit.html.twig', [
            'unit' => $unit,
            'form' => $form,
            'current_section' => 'recursos'
        ]);
    }

    #[Route('/subfamily/new', name: 'app_material_subfamily_new', methods: ['POST'])]
    public function newSubFamily(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? null;

        if (!$name) {
            return new JsonResponse(['error' => 'Nombre requerido'], Response::HTTP_BAD_REQUEST);
        }

        // SubFamily is just a string in the Material entity, so we don't need to create a new entity.
        // By adding it to the UI and then saving a Material with it, it will appear in findAllExistingSubFamilies()
        // in the next form load.

        return new JsonResponse(['success' => true, 'name' => $name]);
    }

    #[Route('/unit/{id}/status', name: 'app_material_unit_status', methods: ['GET', 'POST'])]
    public function changeUnitStatus(Request $request, MaterialUnit $unit, MaterialManager $materialManager, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaterialUnitStatusType::class, ['status' => $unit->getOperationalStatus()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $materialManager->changeUnitStatus($unit, $data['status'], $data['reason']);

            $entityManager->flush();

            $this->addFlash('success', 'Estado modificado correctamente.');
            return $this->redirectToRoute('app_material_show', ['id' => $unit->getMaterial()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('material/unit_status.html.twig', [
            'unit' => $unit,
            'form' => $form,
            'current_section' => 'recursos'
        ]);
    }
}
