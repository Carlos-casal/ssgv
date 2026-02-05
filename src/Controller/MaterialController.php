<?php

namespace App\Controller;

use App\Entity\Material;
use App\Entity\MaterialUnit;
use App\Form\MaterialType;
use App\Form\MaterialUnitType;
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
use Symfony\Component\Routing\Annotation\Route;

#[Route('/material')]
class MaterialController extends AbstractController
{
    #[Route('/', name: 'app_material_index', methods: ['GET'])]
    public function index(Request $request, MaterialRepository $materialRepository, MaterialStockRepository $stockRepository): Response
    {
        $category = $request->query->get('category');
        $size = $request->query->get('size');

        $qb = $materialRepository->createQueryBuilder('m');

        if ($category) {
            $qb->andWhere('m.category = :category')
               ->setParameter('category', $category);
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

        return $this->render('material/new.html.twig', [
            'material' => $material,
            'form' => $form,
            'current_section' => 'recursos'
        ]);
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

        return $this->render('material/edit.html.twig', [
            'material' => $material,
            'form' => $form,
            'current_section' => 'recursos'
        ]);
    }

    #[Route('/{id}/unit/new', name: 'app_material_unit_new', methods: ['GET', 'POST'])]
    public function newUnit(Request $request, Material $material, EntityManagerInterface $entityManager): Response
    {
        $unit = new MaterialUnit();
        $unit->setMaterial($material);
        $form = $this->createForm(MaterialUnitType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($unit);
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
    public function bulkAddUnits(Request $request, Material $material, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $unitsData = $request->request->all('units');
            foreach ($unitsData as $data) {
                $unit = new MaterialUnit();
                $unit->setMaterial($material);
                $unit->setCollectiveNumber($data['collectiveNumber'] ?? null);
                $unit->setSerialNumber($data['serialNumber'] ?? null);
                $unit->setPttStatus($data['pttStatus'] ?? 'OK');
                $unit->setCoverStatus($data['coverStatus'] ?? 'OK');
                $unit->setBatteryStatus($data['batteryStatus'] ?? '100%');
                $entityManager->persist($unit);
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
                $this->getUser(),
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
        
        return $this->redirectToRoute('app_material_index');
    }
}
