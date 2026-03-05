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
use Symfony\Component\Routing\Annotation\Route;

#[Route('/material')]
class MaterialController extends AbstractController
{
    #[Route('/', name: 'app_material_index', methods: ['GET'])]
    public function index(Request $request, MaterialRepository $materialRepository, MaterialStockRepository $stockRepository): Response
    {
        $category = $request->query->get('category');
        $size = $request->query->get('size');
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

        if ($size) {
            $qb->join('m.stocks', 'ms')
                ->andWhere('ms.size = :size')
                ->andWhere('ms.quantity > 0')
                ->setParameter('size', $size);
        }

        $materials = $qb->getQuery()->getResult();

        return $this->render('material/index.html.twig', [
            'materials' => $materials,
            'current_category' => $category,
            'current_size' => $size,
            'current_subfamily' => $subFamily,
            'current_section' => 'recursos'
        ]);
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
            $entityManager->flush();

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
                    ]);
                }
                $entityManager->flush();
            }

            // Handle initial stock from grid
            if ($request->request->has('initial_stock')) {
                $adjustments = $request->request->all('initial_stock');
                $reason = 'Inicialización de stock';
                foreach ($adjustments as $size => $quantity) {
                    if ($quantity > 0) {
                        $materialManager->adjustStock($material, (int)$quantity, $reason, (string)$size);
                    }
                }
                // Handle custom
                $customSize = $request->request->get('custom_size');
                $customQty = (int)$request->request->get('custom_qty');
                if ($customSize && $customQty > 0) {
                    $materialManager->adjustStock($material, $customQty, $reason, $customSize);
                }
            }

            return $this->redirectToRoute('app_material_index', [], Response::HTTP_SEE_OTHER);
        }

        $unitsData = $request->request->all('units_data');

        return $this->render('material/new.html.twig', [
            'material' => $material,
            'form' => $form,
            'units_data' => $unitsData,
            'current_section' => 'recursos'
        ]);
    }

    #[Route('/import/template', name: 'app_material_import_template', methods: ['GET'])]
    public function downloadTemplate(ExcelImportService $importService): Response
    {
        $templatePath = $importService->generateTemplate();

        $response = new BinaryFileResponse($templatePath);
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

            return $this->render('material/import_preview.html.twig', [
                'preview' => $preview,
                'temp_filename' => $tempFilename,
                'current_section' => 'recursos'
            ]);
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
            $result = $importService->processImport($file);

            // Delete temp file
            unlink($tempPath);

            $message = sprintf(
                'Importación completada: %d materiales creados, %d actualizados.',
                $result['created'],
                $result['updated']
            );

            if (!empty($result['errors'])) {
                $message .= ' Errores: ' . implode(', ', $result['errors']);
                $this->addFlash('warning', $message);
            } else {
                $this->addFlash('success', $message);
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error al importar: ' . $e->getMessage());
        }
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
    public function show(Material $material, MaterialMovementRepository $movementRepository): Response
    {
        $movements = $movementRepository->findBy(['material' => $material], ['createdAt' => 'DESC'], 10);

        return $this->render('material/show.html.twig', [
            'material' => $material,
            'material_movements' => $movements,
            'current_section' => 'recursos'
        ]);
    }

    #[Route('/{id}/edit', name: 'app_material_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Material $material, EntityManagerInterface $entityManager, MaterialManager $materialManager): Response
    {
        $form = $this->createForm(MaterialType::class, $material);
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

            $entityManager->flush();

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
                                $history = new MaterialUnitHistory();
                                $history->setMaterialUnit($unit);
                                $history->setStatus($newStatus);
                                $history->setReason($uData['statusReason'] ?? null);
                                $history->setUser($this->getUser());
                                $entityManager->persist($history);
                            }
                            $unit->setOperationalStatus($newStatus);
                        }
                        if (isset($uData['purchasePrice']) && $uData['purchasePrice'] !== '') {
                            $unit->setPurchasePrice(str_replace(',', '.', $uData['purchasePrice']));
                        }
                        if (isset($uData['discountPct']) && $uData['discountPct'] !== '') {
                            $unit->setDiscountPct(str_replace(',', '.', $uData['discountPct']));
                        }
                    }
                }
                $entityManager->flush();

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
                        ]);
                    }
                    $entityManager->flush();
                }
            }

            // Handle initial stock from grid (even in edit, it acts as an addition)
            if ($request->request->has('initial_stock')) {
                $adjustments = $request->request->all('initial_stock');
                $reason = 'Ajuste desde edición';
                foreach ($adjustments as $size => $quantity) {
                    if ($quantity > 0) {
                        $materialManager->adjustStock($material, (int)$quantity, $reason, (string)$size);
                    }
                }
                // Handle custom
                $customSize = $request->request->get('custom_size');
                $customQty = (int)$request->request->get('custom_qty');
                if ($customSize && $customQty > 0) {
                    $materialManager->adjustStock($material, $customQty, $reason, $customSize);
                }
            }

            return $this->redirectToRoute('app_material_index', [], Response::HTTP_SEE_OTHER);
        }

        // Prepare existing units data for the frontend
        $unitsData = $request->request->all('units_data');
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

        return $this->render('material/edit.html.twig', [
            'material' => $material,
            'form' => $form,
            'units_data' => $unitsData,
            'current_section' => 'recursos'
        ]);
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
                $data['size'],
                $data['materialUnit'] ?? null
            );

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
    public function adjustStock(Request $request, Material $material, MaterialManager $materialManager): Response
    {
        $reason = $request->request->get('reason', 'Ajuste manual');

        // 1. Bulk adjustments from standard grid
        $adjustments = $request->request->all('adjustments');
        if (!empty($adjustments)) {
            $materialManager->bulkAdjustStock($material, $adjustments, $reason);
        }

        // 2. Manual entry from custom column
        $customSize = trim((string)$request->request->get('custom_size'));
        $customQty = (int)$request->request->get('custom_qty');
        if ($customSize !== '' && $customQty !== 0) {
            $materialManager->adjustStock($material, $customQty, $reason, $customSize);
        }

        // 3. Individual adjustments (from old logic or API-like single calls)
        $quantity = (int)$request->request->get('quantity');
        $size = $request->request->get('size');
        if ($quantity !== 0 && $size && empty($adjustments)) {
            $materialManager->adjustStock($material, $quantity, $reason, $size);
        }

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

    #[Route('/unit/{id}/status', name: 'app_material_unit_status', methods: ['GET', 'POST'])]
    public function changeUnitStatus(Request $request, MaterialUnit $unit, MaterialManager $materialManager): Response
    {
        $form = $this->createForm(MaterialUnitStatusType::class, ['status' => $unit->getOperationalStatus()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $materialManager->changeUnitStatus($unit, $data['status'], $data['reason']);

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
