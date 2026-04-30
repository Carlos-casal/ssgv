<?php

namespace App\Controller;

use App\Entity\KitTemplate;
use App\Entity\KitTemplateItem;
use App\Entity\MaterialUnit;
use App\Entity\Location;
use App\Entity\MaterialStock;
use App\Entity\Material;
use App\Service\MaterialManager;
use App\Repository\KitTemplateRepository;
use App\Repository\MaterialUnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/kits')]
class KitController extends AbstractController
{
    #[Route('/', name: 'app_kit_index', methods: ['GET'])]
    public function index(MaterialUnitRepository $unitRepository): Response
    {
        // Eager load template and kitLocation to avoid N+1 queries in the list view
        $kits = $unitRepository->createQueryBuilder('u')
            ->leftJoin('u.template', 't')
            ->addSelect('t')
            ->leftJoin('u.kitLocation', 'kl')
            ->addSelect('kl')
            ->where('u.template IS NOT NULL')
            ->getQuery()
            ->getResult();

        $response = $this->render('kit/index.html.twig', [
            'kits' => $kits,
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;
    }

    #[Route('/check-alias', name: 'app_kit_check_alias', methods: ['GET'])]
    public function checkAlias(Request $request, MaterialUnitRepository $unitRepository): Response
    {
        $alias = $request->query->get('alias');
        if (!$alias) {
            return $this->json(['available' => true]);
        }

        $exists = $unitRepository->count(['alias' => $alias]) > 0;

        return $this->json(['available' => !$exists]);
    }

    #[Route('/templates', name: 'app_kit_template_index', methods: ['GET'])]
    public function templateIndex(KitTemplateRepository $templateRepository): Response
    {
        // Eager load items to avoid N+1 queries when counting products in index
        $templates = $templateRepository->createQueryBuilder('t')
            ->leftJoin('t.items', 'i')
            ->addSelect('i')
            ->getQuery()
            ->getResult();

        $response = $this->render('kit/template_index.html.twig', [
            'templates' => $templates,
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;
    }

    #[Route('/templates/seed-defaults', name: 'app_kit_template_seed_defaults', methods: ['GET', 'POST'])]
    public function seedDefaultTemplates(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('seed_defaults', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF inválido.');
        }

        $defaults = [
            [
                'name' => 'Mochila SVB Básica',
                'type' => 'Mochila',
                'items' => [
                    ['name' => 'Tensiómetro', 'qty' => 1, 'nature' => Material::NATURE_TECHNICAL],
                    ['name' => 'Pulsioxímetro', 'qty' => 1, 'nature' => Material::NATURE_TECHNICAL],
                    ['name' => 'Cánula Guedel #0', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Cánula Guedel #1', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Cánula Guedel #2', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Cánula Guedel #3', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Cánula Guedel #5', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Gasas estériles', 'qty' => 35, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Suero fisiológico 10 ml', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Suero fisiológico 30 ml', 'qty' => 3, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Suero fisiológico 100 ml', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Venda crepe 4x5', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Venda crepe 4x7', 'qty' => 3, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Venda crepe 4x10', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Venda crepe 10x10', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Esparadrapo hipoalergénico', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Omnifix', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Clorhexidina', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Agua oxigenada', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Alcohol 96', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Apósitos 7x5', 'qty' => 2, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Apósitos 10x8', 'qty' => 5, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Apósitos 20x10', 'qty' => 5, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'AMBU + mascarilla', 'qty' => 1, 'nature' => Material::NATURE_TECHNICAL],
                    ['name' => 'Manta de Emergencia', 'qty' => 4, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Tijera corta ropa', 'qty' => 1, 'nature' => Material::NATURE_TECHNICAL],
                    ['name' => 'Pinza', 'qty' => 1, 'nature' => Material::NATURE_TECHNICAL],
                    ['name' => 'Spray de frío', 'qty' => 1, 'nature' => Material::NATURE_CONSUMABLE],
                    ['name' => 'Guantes (diferentes tallas)', 'qty' => 6, 'nature' => Material::NATURE_CONSUMABLE],
                ]
            ],
            ['name' => 'Maletín de Oxigenoterapia', 'type' => 'Bolsa', 'items' => []],
            ['name' => 'Riñonera de Intervención Rápida', 'type' => 'Riñonera', 'items' => []],
        ];

        foreach ($defaults as $data) {
            $template = $entityManager->getRepository(KitTemplate::class)->findOneBy(['name' => $data['name']]);
            if (!$template) {
                $template = new KitTemplate();
                $template->setName($data['name']);
                $template->setContainerType($data['type']);
                $entityManager->persist($template);
            }

            // Sync items
            if (!empty($data['items'])) {
                foreach ($data['items'] as $itemData) {
                    $material = $entityManager->getRepository(Material::class)->findOneBy(['name' => $itemData['name']]);

                    $exists = false;
                    foreach ($template->getItems() as $existingItem) {
                        if ($material && $existingItem->getMaterial() === $material) {
                            $existingItem->setQuantity($itemData['qty']);
                            $exists = true;
                            break;
                        } elseif (!$material && $existingItem->getSuggestedName() === $itemData['name']) {
                            $existingItem->setQuantity($itemData['qty']);
                            $exists = true;
                            break;
                        }
                    }

                    if (!$exists) {
                        $item = new KitTemplateItem();
                        if ($material) {
                            $item->setMaterial($material);
                        } else {
                            $item->setSuggestedName($itemData['name']);
                        }
                        $item->setQuantity($itemData['qty']);
                        $template->addItem($item);
                        $entityManager->persist($item);
                    }
                }
            }
        }

        $entityManager->flush();
        $this->addFlash('success', 'Plantillas base creadas correctamente.');

        return $this->redirectToRoute('app_kit_template_index');
    }

    #[Route('/templates/new', name: 'app_kit_template_new', methods: ['GET', 'POST'])]
    public function newTemplate(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('kit_template', $request->request->get('_token'))) {
                throw $this->createAccessDeniedException('Token CSRF inválido.');
            }
            $name = $request->request->get('name');
            $containerType = $request->request->get('container_type');
            $description = $request->request->get('description');
            $items = $request->request->all('items');

            $template = new KitTemplate();
            $template->setName($name);
            $template->setContainerType($containerType);
            $template->setDescription($description);

            foreach ($items as $itemData) {
                if ((empty($itemData['material']) && empty($itemData['suggested_name'])) || empty($itemData['quantity'])) continue;

                $item = new KitTemplateItem();
                if (!empty($itemData['material'])) {
                    $material = $entityManager->getRepository(Material::class)->find($itemData['material']);
                    if ($material) $item->setMaterial($material);
                } else {
                    $item->setSuggestedName($itemData['suggested_name']);
                }

                $item->setQuantity((int)$itemData['quantity']);
                $template->addItem($item);
            }

            $entityManager->persist($template);
            $entityManager->flush();

            $this->addFlash('success', 'Plantilla creada correctamente.');

            return $this->redirectToRoute('app_kit_template_index');
        }

        $materials = $entityManager->getRepository(Material::class)->createQueryBuilder('m')
            ->where('m.nature = :nature')
            ->orWhere('m.category = :category')
            ->setParameter('nature', Material::NATURE_CONSUMABLE)
            ->setParameter('category', 'Sanitario')
            ->getQuery()
            ->getResult();

        $response = $this->render('kit/template_new.html.twig', [
            'materials' => $materials,
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;
    }

    #[Route('/templates/{id}/delete', name: 'app_kit_template_delete', methods: ['POST'])]
    public function deleteTemplate(Request $request, KitTemplate $template, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete_template_' . $template->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF inválido.');
        }

        // Before deleting, nullify references in MaterialUnit to avoid FK issues
        $units = $entityManager->getRepository(MaterialUnit::class)->findBy(['template' => $template]);
        foreach ($units as $unit) {
            $unit->setTemplate(null);
        }

        $entityManager->remove($template);
        $entityManager->flush();

        $this->addFlash('success', 'Plantilla eliminada correctamente.');

        return $this->redirectToRoute('app_kit_template_index');
    }

    #[Route('/templates/{id}/edit', name: 'app_kit_template_edit', methods: ['GET', 'POST'])]
    public function editTemplate(Request $request, KitTemplate $template, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('kit_template', $request->request->get('_token'))) {
                throw $this->createAccessDeniedException('Token CSRF inválido.');
            }
            $name = $request->request->get('name');
            $containerType = $request->request->get('container_type');
            $description = $request->request->get('description');
            $items = $request->request->all('items');

            $template->setName($name);
            $template->setContainerType($containerType);
            $template->setDescription($description);

            // Clear existing items
            foreach ($template->getItems() as $item) {
                $entityManager->remove($item);
            }
            $template->getItems()->clear();

            foreach ($items as $itemData) {
                if ((empty($itemData['material']) && empty($itemData['suggested_name'])) || empty($itemData['quantity'])) continue;

                $item = new KitTemplateItem();
                if (!empty($itemData['material'])) {
                    $item->setMaterial($entityManager->getReference(Material::class, $itemData['material']));
                } else {
                    $item->setSuggestedName($itemData['suggested_name']);
                }
                $item->setQuantity((int)$itemData['quantity']);
                $template->addItem($item);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Plantilla actualizada correctamente.');

            return $this->redirectToRoute('app_kit_template_index');
        }

        $materials = $entityManager->getRepository(Material::class)->createQueryBuilder('m')
            ->where('m.nature = :nature')
            ->orWhere('m.category = :category')
            ->setParameter('nature', Material::NATURE_CONSUMABLE)
            ->setParameter('category', 'Sanitario')
            ->getQuery()
            ->getResult();

        $response = $this->render('kit/template_edit.html.twig', [
            'template' => $template,
            'materials' => $materials,
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;
    }

    #[Route('/{id}/edit', name: 'app_kit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MaterialUnit $unit, EntityManagerInterface $entityManager): Response
    {
        if (!$unit->getTemplate()) {
            throw $this->createNotFoundException('Este material no es un botiquín.');
        }

        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('kit_edit_' . $unit->getId(), $request->request->get('_token'))) {
                throw $this->createAccessDeniedException('Token CSRF inválido.');
            }

            $templateId = $request->request->get('template_id');
            $alias = $request->request->get('alias');
            $serialNumber = $request->request->get('serial_number');

            if (empty($serialNumber)) {
                $serialNumber = null;
            }

            $template = $entityManager->getRepository(KitTemplate::class)->find($templateId);
            if (!$template) {
                throw $this->createNotFoundException('Plantilla no encontrada.');
            }

            $unit->setAlias($alias);
            $unit->setSerialNumber($serialNumber);
            $unit->setTemplate($template);

            // Update location name to reflect new alias/SN
            $location = $unit->getKitLocation();
            if ($location) {
                $location->setName('Botiquín: ' . ($alias ?: $serialNumber));
            }

            $entityManager->flush();

            $this->addFlash('success', 'Botiquín actualizado correctamente.');

            return $this->redirectToRoute('app_kit_index');
        }

        $response = $this->render('kit/edit.html.twig', [
            'unit' => $unit,
            'templates' => $entityManager->getRepository(KitTemplate::class)->findAll(),
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;
    }

    #[Route('/new', name: 'app_kit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('kit_new', $request->request->get('_token'))) {
                throw $this->createAccessDeniedException('Token CSRF inválido.');
            }

            $alias = $request->request->get('alias');
            if ($alias) {
                $exists = $entityManager->getRepository(MaterialUnit::class)->count(['alias' => $alias]) > 0;
                if ($exists) {
                    $this->addFlash('error', 'El alias "' . $alias . '" ya está en uso por otro botiquín o equipo.');
                    return $this->render('kit/new.html.twig', [
                        'templates' => $entityManager->getRepository(KitTemplate::class)->findAll(),
                    ]);
                }
            }

            $session = $request->getSession();
            $session->set('draft_kit', [
                'template_id' => $request->request->get('template_id'),
                'alias' => $alias,
                'serial_number' => $request->request->get('serial_number'),
                'brand' => $request->request->get('brand'),
                'model' => $request->request->get('model'),
                'barcode' => $request->request->get('barcode'),
                'subfamily' => $request->request->get('subfamily'),
                'supplier' => $request->request->get('supplier'),
                'purchase_price' => $request->request->get('purchase_price'),
                'margin_percentage' => $request->request->get('margin_percentage'),
                'iva' => $request->request->get('iva'),
            ]);

            return $this->redirectToRoute('app_kit_new_preview');
        }

        $response = $this->render('kit/new.html.twig', [
            'templates' => $entityManager->getRepository(KitTemplate::class)->findAll(),
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;
    }


    #[Route('/new/preview', name: 'app_kit_new_preview', methods: ['GET'])]
    public function newPreview(Request $request, EntityManagerInterface $entityManager, MaterialManager $materialManager): Response
    {
        $centralWarehouse = $materialManager->getPharmacyWarehouse(); // Force Pharmacy as primary source for kits
        $session = $request->getSession();
        $draft = $session->get('draft_kit');

        if (!$draft) {
            $this->addFlash('warning', 'No hay ningún botiquín en proceso de registro.');
            return $this->redirectToRoute('app_kit_new');
        }

        $template = $entityManager->getRepository(KitTemplate::class)->find($draft['template_id']);
        if (!$template) {
            throw $this->createNotFoundException('Plantilla no encontrada.');
        }

        // Create a DUMMY MaterialUnit for the view (NOT PERSISTED)
        $unit = new MaterialUnit();
        $unit->setAlias($draft['alias']);
        $unit->setSerialNumber($draft['serial_number']);
        $unit->setTemplate($template);
        
        // Use the appropriate material for the dummy unit
        $unit->setMaterial($this->getOrCreateKitMaterial(
            $entityManager,
            $template->getName(),
            $draft['brand'] ?? null,
            $draft['model'] ?? null,
            $draft['barcode'] ?? null,
            $draft['subfamily'] ?? null
        ));

        $proposals = [];
        $shortages = [];
        $warehouseOptions = [];
        $reservedStock = []; // Track stock already proposed to avoid double-allocation

        // 1. Proposals for items IN THE TEMPLATE (FIFO)
        $dummyLocation = new Location(); // Not persisted

        foreach ($template->getItems() as $item) {
            $material = $item->getMaterial();
            if (!$material) continue; // Skip items that are only suggestions for now in auto-refill logic
            $defaultWarehouse = $materialManager->getDefaultLocation($material);
            $this->addMaterialOptionsToRefill($material, $unit, $dummyLocation, $defaultWarehouse, $proposals, $shortages, $warehouseOptions, $entityManager, $item->getQuantity(), false, $reservedStock);
        }

        // 2. Proposals for ALL OTHER materials in inventory
        $allMaterials = $entityManager->getRepository(Material::class)->findAll();
        foreach ($allMaterials as $m) {
            if (isset($warehouseOptions[$m->getId()])) continue;
            $defaultWarehouse = $materialManager->getDefaultLocation($m);
            $this->addMaterialOptionsToRefill($m, $unit, $dummyLocation, $defaultWarehouse, $proposals, $shortages, $warehouseOptions, $entityManager, 0, true, $reservedStock);
        }

        // Sort warehouse options: Non-busy first (Warehouse), then busy ones (Other locations/unassigned).
        // Within groups, sort by ID ascending (oldest first).
        foreach ($warehouseOptions as $matId => &$options) {
            usort($options, function($a, $b) {
                $aBusy = $a['busy'] ?? false;
                $bBusy = $b['busy'] ?? false;

                if ($aBusy !== $bBusy) {
                    return $aBusy ? 1 : -1;
                }

                if ($a['id'] === $b['id']) return 0;

                // NO_BATCH and NONE should be treated as high ID to go last in their group
                $aId = ($a['id'] === 'NO_BATCH' || $a['id'] === 'NONE') ? 999999 : (int)$a['id'];
                $bId = ($b['id'] === 'NO_BATCH' || $b['id'] === 'NONE') ? 999999 : (int)$b['id'];

                return $aId <=> $bId;
            });
        }

        $templateQuantities = [];
        foreach ($template->getItems() as $item) {
            if ($item->getMaterial()) {
                $templateQuantities[$item->getMaterial()->getId()] = $item->getQuantity();
            }
        }

        $response = $this->render('kit/refill_preview.html.twig', [
            'unit' => $unit,
            'proposals' => $proposals,
            'shortages' => $shortages,
            'currentContents' => [],
            'warehouseOptions' => $warehouseOptions,
            'centralWarehouse' => $centralWarehouse,
            'is_new' => true,
            'allMaterials' => $allMaterials,
            'templateQuantities' => $templateQuantities
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;

    }

    #[Route('/{id}/inventory', name: 'app_kit_inventory', methods: ['GET'])]
    public function inventory(int $id, MaterialUnitRepository $unitRepository, \App\Repository\MaterialMovementRepository $movementRepository, EntityManagerInterface $entityManager, MaterialManager $materialManager): Response
    {
        // Eager load everything to ensure consistency
        $unit = $unitRepository->createQueryBuilder('u')
            ->leftJoin('u.template', 't')
            ->addSelect('t')
            ->leftJoin('t.items', 'ti')
            ->addSelect('ti')
            ->leftJoin('ti.material', 'tim')
            ->addSelect('tim')
            ->leftJoin('u.kitLocation', 'kl')
            ->addSelect('kl')
            ->leftJoin('kl.stocks', 'ks')
            ->addSelect('ks')
            ->leftJoin('ks.material', 'ksm')
            ->addSelect('ksm')
            ->leftJoin('kl.units', 'ku')
            ->addSelect('ku')
            ->leftJoin('ku.material', 'kum')
            ->addSelect('kum')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$unit || !$unit->getTemplate()) {
            throw $this->createNotFoundException('Este material no es un botiquín.');
        }

        // Force refresh to bypass any local object cache that might be stale after a back-navigation
        $entityManager->refresh($unit);
        if ($unit->getKitLocation()) {
            $entityManager->refresh($unit->getKitLocation());
            $movements = $movementRepository->createQueryBuilder('m')
                ->where('m.origin = :location OR m.destination = :location')
                ->setParameter('location', $unit->getKitLocation())
                ->orderBy('m.createdAt', 'DESC')
                ->setMaxResults(50)
                ->getQuery()
                ->getResult();
        } else {
            $movements = [];
        }

        // --- Refactored Inventory Logic ---
        $inventoryData = [];
        $displayedMaterialIds = [];
        $location = $unit->getKitLocation();
        
        // 1. Aggregate current contents by Material ID
        $contents = [];
        $unitCounts = []; // To keep track of units for deduplication of technical stock
        if ($location) {
            // Process Units first to establish the baseline for technical items
            foreach ($location->getUnits() as $u) {
                if ($u->getId() === $unit->getId()) continue; // Skip container
                $mid = $u->getMaterial()->getId();
                if (!isset($contents[$mid])) {
                    $contents[$mid] = ['material' => $u->getMaterial(), 'quantity' => 0, 'aliases' => [], 'sources' => []];
                }
                $contents[$mid]['quantity'] += 1;
                $contents[$mid]['sources'][] = 'Unidad Individual';
                $unitCounts[$mid] = ($unitCounts[$mid] ?? 0) + 1;

                $info = [];
                if ($u->getAlias()) $info[] = $u->getAlias();
                if ($u->getSerialNumber()) $info[] = 'S/N: ' . $u->getSerialNumber();
                if ($u->getCollectiveNumber()) $info[] = '#' . $u->getCollectiveNumber();
                
                if (!empty($info)) {
                    $contents[$mid]['aliases'][] = implode(' • ', $info);
                }
            }

            // Process Stocks, deduplicating shadow stock for technical items
            foreach ($location->getStocks() as $stock) {
                if ($stock->getQuantity() <= 0) continue;
                $mid = $stock->getMaterial()->getId();
                $material = $stock->getMaterial();

                $qty = $stock->getQuantity();
                if ($material->getNature() === \App\Entity\Material::NATURE_TECHNICAL) {
                    $uCount = $unitCounts[$mid] ?? 0;
                    // Technical stock is often a shadow of the units. Only count what's extra.
                    $qty = max(0, $qty - $uCount);
                }

                if ($qty > 0) {
                    if (!isset($contents[$mid])) {
                        $contents[$mid] = ['material' => $material, 'quantity' => 0, 'aliases' => [], 'sources' => []];
                    }
                    $contents[$mid]['quantity'] += $qty;
                    $contents[$mid]['sources'][] = 'Stock Granel: ' . $qty;
                }
            }
        }

        // 2. Process Template Items and match with contents
        $templateRows = [];
        $customQuantities = $unit->getCustomQuantities() ?: [];

        foreach ($unit->getTemplate()->getItems() as $item) {
            $idealQuantity = $customQuantities[$item->getId()] ?? $item->getQuantity();
            $row = [
                'templateItem' => $item,
                'material' => $item->getMaterial(),
                'currentQty' => 0,
                'idealQuantity' => $idealQuantity,
                'isMatch' => false,
                'matchedMaterial' => null,
                'matchedAliases' => []
            ];

            if ($item->getMaterial()) {
                $mid = $item->getMaterial()->getId();
                $row['availableStock'] = $materialManager->getAvailableStock($item->getMaterial());
                if (isset($contents[$mid])) {
                    $row['currentQty'] = $contents[$mid]['quantity'];
                    $row['sources'] = $contents[$mid]['sources'] ?? [];
                    $row['matchedAliases'] = $contents[$mid]['aliases'] ?? [];
                    $displayedMaterialIds[] = $mid;
                }
            } else {
                // Fuzzy matching for suggested items without linked material
                $normalize = function($str) {
                    $str = strtolower(trim($str));
                    $str = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $str);
                    return preg_replace('/[^a-z0-9 ]/', '', $str);
                };
                
                $suggestedNameNormalized = $normalize($item->getSuggestedName());
                foreach ($contents as $mid => $data) {
                    $matNameNormalized = $normalize($data['material']->getName());
                    if (str_contains($matNameNormalized, $suggestedNameNormalized) || str_contains($suggestedNameNormalized, $matNameNormalized)) {
                        $row['currentQty'] += $data['quantity'];
                        $row['matchedMaterial'] = $data['material'];
                        $row['isMatch'] = true;
                        $row['matchedAliases'] = array_merge($row['matchedAliases'], $data['aliases'] ?? []);
                        $row['sources'] = $data['sources'] ?? [];
                        $displayedMaterialIds[] = $mid;
                    }
                }
            }
            $templateRows[] = $row;
        }

        // 3. Process Extras (contents not in template)
        $extraRows = [];
        foreach ($contents as $mid => $data) {
            if (!in_array($mid, $displayedMaterialIds)) {
                $extraRows[] = [
                    'material' => $data['material'],
                    'quantity' => $data['quantity'],
                    'aliases' => $data['aliases'] ?? [],
                    'sources' => $data['sources'] ?? []
                ];
            }
        }

        $response = $this->render('kit/inventory.html.twig', [
            'unit' => $unit,
            'movements' => $movements,
            'templateRows' => $templateRows,
            'extraRows' => $extraRows
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        // Prevent browser caching to ensure latest inventory is shown when navigating back
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        return $response;
    }

    #[Route('/{id}/update-ideal-quantity', name: 'app_kit_update_ideal_quantity', methods: ['POST'])]
    public function updateIdealQuantity(int $id, Request $request, MaterialUnitRepository $unitRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $unit = $unitRepository->find($id);
        if (!$unit) {
            return new JsonResponse(['success' => false, 'message' => 'Kit not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $itemId = $data['itemId'] ?? null;
        $quantity = $data['quantity'] ?? null;

        if ($itemId === null || $quantity === null) {
            return new JsonResponse(['success' => false, 'message' => 'Missing data'], 400);
        }

        $customQuantities = $unit->getCustomQuantities() ?: [];
        $customQuantities[$itemId] = (int)$quantity;
        $unit->setCustomQuantities($customQuantities);

        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/{id}/consume', name: 'app_kit_consume', methods: ['GET', 'POST'])]
    public function consume(Request $request, MaterialUnit $unit, MaterialManager $materialManager, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('kit_consume', $request->request->get('_token'))) {
                throw $this->createAccessDeniedException('Token CSRF inválido.');
            }
            $consumptions = $request->request->all('consumption');
            $location = $unit->getKitLocation();

            foreach ($consumptions as $materialId => $quantity) {
                if ($quantity <= 0) continue;

                $material = $entityManager->getRepository(Material::class)->find($materialId);
                if ($material) {
                    $materialManager->adjustStock(
                        $material,
                        -(int)$quantity,
                        'Consumo manual de botiquín ' . $unit->getAlias(),
                        $location
                    );
                }
            }

            $this->addFlash('success', 'Consumo registrado correctamente.');
            return $this->redirectToRoute('app_kit_inventory', ['id' => $unit->getId()]);
        }

        $response = $this->render('kit/consume.html.twig', [
            'unit' => $unit,
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;
    }

    #[Route('/{id}/refill', name: 'app_kit_refill', methods: ['POST'])]
    public function refill(MaterialUnit $unit): Response
    {
        return $this->redirectToRoute('app_kit_refill_preview', ['id' => $unit->getId()]);
    }

    #[Route('/{id}/refill/preview', name: 'app_kit_refill_preview', methods: ['GET'])]
    public function refillPreview(MaterialUnit $unit, MaterialManager $materialManager, EntityManagerInterface $entityManager): Response
    {
        $centralWarehouse = $materialManager->getPharmacyWarehouse(); // Force Pharmacy as primary source for kits
        $template = $unit->getTemplate();
        if (!$template) {
            throw $this->createNotFoundException('Este botiquín no tiene una plantilla asignada.');
        }

        $kitLocation = $unit->getKitLocation();

        $proposals = [];
        $shortages = [];
        $currentContents = [];
        $warehouseOptions = []; // To store all available batches/units for each material
        $reservedStock = []; // Track stock already proposed to avoid double-allocation

        // 1. Map existing stock in the kit (to avoid "disappearance" on reload/back)
        $unitCounts = [];
        foreach ($kitLocation->getUnits() as $kitUnit) {
            if ($kitUnit->getId() === $unit->getId()) continue; // Skip container
            $mid = $kitUnit->getMaterial()->getId();
            $unitCounts[$mid] = ($unitCounts[$mid] ?? 0) + 1;

            $currentContents[] = [
                'material' => $kitUnit->getMaterial(),
                'quantity' => 1,
                'batch' => null,
                'unit' => $kitUnit
            ];
        }

        foreach ($kitLocation->getStocks() as $stock) {
            if ($stock->getQuantity() <= 0) continue;
            $mid = $stock->getMaterial()->getId();
            $material = $stock->getMaterial();

            $qty = $stock->getQuantity();
            if ($material->getNature() === \App\Entity\Material::NATURE_TECHNICAL) {
                $uCount = $unitCounts[$mid] ?? 0;
                $qty = max(0, $qty - $uCount);
            }

            if ($qty > 0) {
                $currentContents[] = [
                    'material' => $material,
                    'quantity' => $qty,
                    'batch' => $stock->getBatch(),
                    'unit' => null
                ];
            }
        }

        // 2. Proposals for items IN THE TEMPLATE (FIFO)
        foreach ($template->getItems() as $item) {
            $material = $item->getMaterial();
            if (!$material) continue; // Skip suggestions
            $defaultWarehouse = $materialManager->getDefaultLocation($material);
            $this->addMaterialOptionsToRefill($material, $unit, $kitLocation, $defaultWarehouse, $proposals, $shortages, $warehouseOptions, $entityManager, $item->getQuantity(), false, $reservedStock);
        }

        // 3. Proposals for ALL OTHER materials in inventory (empty lists for manual addition)
        $allMaterials = $entityManager->getRepository(Material::class)->findAll();
        foreach ($allMaterials as $m) {
            if (isset($warehouseOptions[$m->getId()])) continue; // Skip if already processed for template
            $defaultWarehouse = $materialManager->getDefaultLocation($m);
            $this->addMaterialOptionsToRefill($m, $unit, $kitLocation, $defaultWarehouse, $proposals, $shortages, $warehouseOptions, $entityManager, 0, true, $reservedStock);
        }

        // Sort warehouse options: Non-busy first (Warehouse), then busy ones (Other locations/unassigned).
        // Within groups, sort by ID ascending (oldest first).
        foreach ($warehouseOptions as $matId => &$options) {
            usort($options, function($a, $b) {
                $aBusy = $a['busy'] ?? false;
                $bBusy = $b['busy'] ?? false;

                if ($aBusy !== $bBusy) {
                    return $aBusy ? 1 : -1;
                }

                if ($a['id'] === $b['id']) return 0;

                // NO_BATCH and NONE should be treated as high ID to go last in their group
                $aId = ($a['id'] === 'NO_BATCH' || $a['id'] === 'NONE') ? 999999 : (int)$a['id'];
                $bId = ($b['id'] === 'NO_BATCH' || $b['id'] === 'NONE') ? 999999 : (int)$b['id'];

                return $aId <=> $bId;
            });
        }

        $templateQuantities = [];
        foreach ($template->getItems() as $item) {
            if ($item->getMaterial()) {
                $templateQuantities[$item->getMaterial()->getId()] = $item->getQuantity();
            }
        }

        $response = $this->render('kit/refill_preview.html.twig', [
            'unit' => $unit,
            'proposals' => $proposals,
            'shortages' => $shortages,
            'currentContents' => $currentContents,
            'warehouseOptions' => $warehouseOptions,
            'centralWarehouse' => $centralWarehouse,
            'allMaterials' => $allMaterials,
            'templateQuantities' => $templateQuantities
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;

    }

    private function addMaterialOptionsToRefill(
        Material $material,
        MaterialUnit $unit,
        Location $kitLocation,
        Location $centralWarehouse,
        &$proposals,
        &$shortages,
        &$warehouseOptions,
        EntityManagerInterface $entityManager,
        int $idealQty = 0,
        bool $manualOnly = false,
        array &$reservedStock = []
    ): void {
        // Calculate current stock in the kit correctly
        $currentQty = 0;
        if ($kitLocation->getId()) {
            // 1. Count bulk stock (MaterialStock) - Works for Consumables AND technical items without S/N
            $stocks = $entityManager->getRepository(MaterialStock::class)->findBy([
                'material' => $material,
                'location' => $kitLocation
            ]);
            foreach ($stocks as $s) $currentQty += $s->getQuantity();

            // 2. For Technical materials, ALSO count physical units (MaterialUnit) assigned here
            // We deduplicate to avoid double-counting "shadow" stock maintained by MaterialManager
            if ($material->getNature() !== Material::NATURE_CONSUMABLE) {
                $qb = $entityManager->getRepository(MaterialUnit::class)->createQueryBuilder('u')
                    ->select('COUNT(u.id)')
                    ->where('u.material = :material')
                    ->andWhere('u.location = :location')
                    ->setParameter('material', $material)
                    ->setParameter('location', $kitLocation);

                if ($unit->getId()) {
                    $qb->andWhere('u.id != :containerId')
                       ->setParameter('containerId', $unit->getId());
                }
                $unitCount = (int)$qb->getQuery()->getSingleScalarResult();
                // Deduction logic: total physical count is the number of units PLUS any extra bulk stock
                $currentQty = $unitCount + max(0, $currentQty - $unitCount);
            }
        }
    

        $needed = $idealQty - $currentQty;
        if ($needed <= 0 && !$manualOnly) return;

        $options = [];
        $availableInWarehouse = 0;

        if ($material->getNature() === Material::NATURE_CONSUMABLE) {
            $qb = $entityManager->getRepository(MaterialStock::class)->createQueryBuilder('ms')
                ->leftJoin('ms.batch', 'b')
                ->join('ms.location', 'l')
                ->where('ms.material = :material')
                ->andWhere('ms.quantity > 0')
                ->setParameter('material', $material);

            // Fetch everything but filter 'busy' in loop
            $stocks = $qb->orderBy('b.createdAt', 'ASC') // FIFO: Oldest stock first
                ->addOrderBy('b.expirationDate', 'ASC')
                ->addOrderBy('b.id', 'ASC')
                ->getQuery()
                ->getResult();

            $foundAutoSelect = false;
            foreach ($stocks as $stock) {
                $stockId = $stock->getId();
                $isBusy = ($stock->getLocation() && $stock->getLocation()->getType() !== Location::TYPE_WAREHOUSE);

                // Skip if it is ALREADY in the current kit (avoid duplicates)
                if ($stock->getLocation() === $kitLocation) continue;

                // Important: Show the NET available quantity (original - reserved by other items)
                $reserved = $reservedStock[$stockId] ?? 0;
                $netAvailable = max(0, $stock->getQuantity() - $reserved);

                // For consumables, the option ID MUST be the MaterialStock ID to ensure uniqueness across locations
                $options[] = [
                    'id' => $stockId,
                    'batch_id' => $stock->getBatch() ? $stock->getBatch()->getId() : 'NO_BATCH',
                    'label' => $stock->getBatch() ? 'Lote: ' . $stock->getBatch()->getBatchNumber() . ' (Exp: ' . ($stock->getBatch()->getExpirationDate() ? $stock->getBatch()->getExpirationDate()->format('d/m/Y') : 'N/A') . ')' : 'Sin Lote',
                    'available' => $netAvailable,
                    'busy' => $isBusy,
                    'selected' => false, // Will be overridden by proposal matching in Twig
                    'locationName' => $stock->getLocation() ? $stock->getLocation()->getName() : 'Sin asignar',
                    'locationId' => $stock->getLocation() ? $stock->getLocation()->getId() : null
                ];

                if (!$isBusy) {
                    $availableInWarehouse += $netAvailable;
                }
            }

            // Initial FIFO proposal based on warehouse stocks
            if (!$manualOnly) {
                $remainingNeeded = $needed;
                foreach ($stocks as $stock) {
                    $stockId = $stock->getId();
                    $isBusy = ($stock->getLocation() && $stock->getLocation()->getType() !== Location::TYPE_WAREHOUSE);
                    // Skip stocks already assigned to any kit or the current kit
                    if ($isBusy || $stock->getLocation() === $kitLocation) continue;

                    // Subtract already reserved quantity from this stock record (in case material is repeated in template)
                    $reserved = $reservedStock[$stockId] ?? 0;
                    $available = $stock->getQuantity() - $reserved;
                    if ($available <= 0) continue;

                    if ($remainingNeeded <= 0) break;
                    $take = min($remainingNeeded, $available);
                    $proposals[] = [
                        'material' => $material,
                        'quantity' => $take,
                        'origin' => $stock->getLocation(),
                        'batch' => $stock->getBatch(),
                        'stock_id' => $stockId,
                        'unit' => null
                    ];
                    $reservedStock[$stockId] = $reserved + $take;
                    $remainingNeeded -= $take;
                }

                if ($remainingNeeded > 0) {
                    $shortages[] = [
                        'material' => $material,
                        'needed' => $remainingNeeded,
                        'alternatives' => $this->findAlternativeLocations($material, $centralWarehouse, $kitLocation, $entityManager)
                    ];

                    // ONLY add a placeholder row if we found ZERO stock in warehouse.
                    // If we found some stock, we don't add a second "shortage" row to avoid "repeats".
                    if ($availableInWarehouse <= 0) {
                        $proposals[] = [
                            'material' => $material,
                            'quantity' => $remainingNeeded,
                            'origin' => $centralWarehouse,
                            'batch' => null,
                            'unit' => null,
                            'placeholder' => true
                        ];
                    }
                }
            }
        } else {
            // Technical Equipment - Get ALL units
            $qb = $entityManager->getRepository(MaterialUnit::class)->createQueryBuilder('u')
                ->leftJoin('u.location', 'l')
                ->where('u.material = :material')
                ->andWhere('u.operationalStatus = :status')
                ->setParameter('material', $material)
                ->setParameter('status', 'OPERATIVO');

            $allUnits = $qb->orderBy('u.id', 'ASC') // Registration date proxy
                ->getQuery()
                ->getResult();

            $warehouseUnits = [];
            $foundAutoSelect = false;

            foreach ($allUnits as $u) {
                $uId = $u->getId();
                // Skip the kit container itself
                if ($unit->getId() && $uId === $unit->getId()) continue;

                // Skip if it is ALREADY in the current kit
                if ($u->getLocation() === $kitLocation) continue;

                // Check if already reserved by a previous item in this template
                $isReserved = $reservedStock['unit_' . $uId] ?? false;
                $netAvailable = $isReserved ? 0 : 1;

                $isBusy = ($u->getLocation() === null || $u->getLocation()->getType() !== Location::TYPE_WAREHOUSE);
                $label = $u->getAlias() ?: ($u->getSerialNumber() ?: 'Unidad ' . $uId);

                $options[] = [
                    'id' => $uId,
                    'label' => $label,
                    'available' => $netAvailable,
                    'busy' => $isBusy,
                    'selected' => false, // Will be overridden by proposal matching in Twig
                    'locationName' => $u->getLocation() ? $u->getLocation()->getName() : 'Sin ubicación / Sin asignar',
                    'locationId' => $u->getLocation() ? $u->getLocation()->getId() : null
                ];

                if (!$isBusy) {
                    $availableInWarehouse += $netAvailable;
                    if (!$isReserved) {
                        $warehouseUnits[] = $u;
                    }
                }
            }

            if (!$manualOnly) {
                // Initial FIFO proposal from warehouse units
                $takeCount = min($needed, count($warehouseUnits));
                for ($i = 0; $i < $takeCount; $i++) {
                    $u = $warehouseUnits[$i];
                    $proposals[] = [
                        'material' => $material,
                        'quantity' => 1,
                        'origin' => $u->getLocation() ?: $centralWarehouse,
                        'batch' => null,
                        'unit' => $u,
                        'unit_id' => $u->getId()
                    ];
                    $reservedStock['unit_' . $u->getId()] = true;
                }

                if ($needed > $availableInWarehouse) {
                    $shortages[] = [
                        'material' => $material,
                        'needed' => $needed - $availableInWarehouse,
                        'alternatives' => $this->findAlternativeLocations($material, $centralWarehouse, $kitLocation, $entityManager)
                    ];

                    // ONLY add a placeholder if we found NO units at all in warehouse
                    if ($availableInWarehouse <= 0) {
                        $proposals[] = [
                            'material' => $material,
                            'quantity' => $needed,
                            'origin' => $centralWarehouse,
                            'batch' => null,
                            'unit' => null,
                            'placeholder' => true
                        ];
                    }
                }
            }
        }
        $warehouseOptions[$material->getId()] = $options;
    }

    #[Route('/{id}/delete', name: 'app_kit_delete', methods: ['POST'])]
    public function deleteKit(Request $request, MaterialUnit $unit, EntityManagerInterface $entityManager, MaterialManager $materialManager): Response
    {
        if (!$this->isCsrfTokenValid('delete_kit_' . $unit->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF inválido.');
        }

        if (!$unit->getTemplate()) {
            throw $this->createNotFoundException('Este material no es un botiquín.');
        }

        $location = $unit->getKitLocation();
        if ($location) {
            $centralWarehouse = $materialManager->getCentralWarehouse();

            // 1. Move all consumables (MaterialStock) back to central warehouse
            $stocks = $entityManager->getRepository(\App\Entity\MaterialStock::class)->findBy(['location' => $location]);
            foreach ($stocks as $stock) {
                if ($stock->getQuantity() > 0) {
                    $materialManager->transfer(
                        $stock->getMaterial(),
                        $location,
                        $materialManager->getDefaultLocation($stock->getMaterial()),
                        $stock->getQuantity(),
                        'Devolución por eliminación de botiquín ' . ($unit->getAlias() ?: $unit->getSerialNumber()),
                        null,
                        null,
                        $stock->getBatch(),
                        $stock
                    );
                }
            }

            // 2. Move all technical units (MaterialUnit) back to central warehouse
            $units = $entityManager->getRepository(MaterialUnit::class)->findBy(['location' => $location]);
            foreach ($units as $otherUnit) {
                // Skip the kit container itself
                if ($otherUnit->getId() === $unit->getId()) continue;

                $materialManager->transfer(
                    $otherUnit->getMaterial(),
                    $location,
                    $materialManager->getDefaultLocation($otherUnit->getMaterial()),
                    1,
                    'Devolución por eliminación de botiquín ' . ($unit->getAlias() ?: $unit->getSerialNumber()),
                    null,
                    $otherUnit,
                    null
                );
            }

            // Flush transfers
            $entityManager->flush();

            // 2b. Clean up orphan stocks (quantity 0) to avoid showing empty rows in dashboard
            foreach ($location->getStocks() as $stock) {
                if ($stock->getQuantity() <= 0) {
                    $entityManager->remove($stock);
                }
            }
            $entityManager->flush();

            // 3. Manually nullify MaterialMovement references to this location (destinations or origins)
            $entityManager->createQueryBuilder()
                ->update(\App\Entity\MaterialMovement::class, 'm')
                ->set('m.origin', ':nullValue')
                ->where('m.origin = :loc')
                ->setParameter('nullValue', null)
                ->setParameter('loc', $location)
                ->getQuery()
                ->execute();

            $entityManager->createQueryBuilder()
                ->update(\App\Entity\MaterialMovement::class, 'm')
                ->set('m.destination', ':nullValue')
                ->where('m.destination = :loc')
                ->setParameter('nullValue', null)
                ->setParameter('loc', $location)
                ->getQuery()
                ->execute();

            // 4. Break the circular reference: Unit -> Location -> Unit
            $unit->setKitLocation(null);
            $entityManager->flush();

            // 5. Remove the Location entity
            $entityManager->remove($location);
            $entityManager->flush();
        }

        // 6. Final cleanup:
        // If this was a "Kit Container" material (specifically created as a kit), we delete the unit.
        // Otherwise (e.g., a technical device temporarily used as a kit), we just return it to central.
        if ($unit->getMaterial() && $unit->getMaterial()->getName() === 'Botiquín') {
            $entityManager->remove($unit);
            $msg = 'Botiquín eliminado completamente (se han retirado todas las referencias de inventario).';
        } else {
            $unit->setTemplate(null);
            $unit->setKitLocation(null);
            $unit->setLocation($materialManager->getDefaultLocation($unit->getMaterial()));
            $msg = 'Botiquín desmantelado correctamente (el equipo técnico ha sido devuelto al Almacén Central).';
        }

        $entityManager->flush();

        $this->addFlash('success', $msg);

        return $this->redirectToRoute('app_kit_index');
    }

    #[Route('/{id}/refill/confirm', name: 'app_kit_refill_confirm', methods: ['POST'])]
    #[Route('/new/confirm', name: 'app_kit_new_confirm', methods: ['POST'], priority: 2)]
    public function refillConfirm(Request $request, ?MaterialUnit $unit, MaterialManager $materialManager, EntityManagerInterface $entityManager): Response
    {
        $isNew = $request->attributes->get('_route') === 'app_kit_new_confirm';
        $session = $request->getSession();

        if (!$this->isCsrfTokenValid($isNew ? 'kit_new_confirm' : 'kit_refill_confirm', $request->request->get('_token'))) {
            return new Response('Token CSRF inválido.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($isNew) {
            $draft = $session->get('draft_kit');
            if (!$draft) {
                $this->addFlash('error', 'Sesión expirada o datos no encontrados.');
                return $this->redirectToRoute('app_kit_new');
            }
            $unit = $this->finalizeKitCreation($draft, $entityManager, $materialManager);
            $session->remove('draft_kit');
        }

        if (!$unit) {
            throw $this->createNotFoundException('Botiquín no encontrado.');
        }

        $proposalsData = $request->request->get('proposals_data');
        $proposals = $proposalsData ? json_decode($proposalsData, true) : [];
        $kitLocation = $unit->getKitLocation();

        if (empty($proposals)) {
            $this->addFlash('warning', 'No se han recibido datos de la propuesta.');
            return $this->redirectToRoute('app_kit_inventory', ['id' => $unit->getId()]);
        }

        // Pre-process: Group proposals by stock_id (for consumables) or unit_id (for technical)
        // to handle duplicates from the UI and sum their quantities.
        $finalProposals = [];
        foreach ($proposals as $p) {
            $matId = $p['material_id'] ?? 0;
            $sId = $p['stock_id'] ?? null;
            $uId = $p['unit_id'] ?? null;
            
            if (!$matId || (!$sId && !$uId)) continue;
            
            $key = $sId ? "stock_$sId" : "unit_$uId";
            if (!isset($finalProposals[$key])) {
                $finalProposals[$key] = $p;
            } else {
                $finalProposals[$key]['quantity'] += (int)$p['quantity'];
            }
        }

        try {
            $entityManager->wrapInTransaction(function() use ($finalProposals, $entityManager, $materialManager, $kitLocation, $unit, $proposalsData) {
                foreach ($finalProposals as $p) {
                    $materialId = (int)($p['material_id'] ?? 0);
                    if (!$materialId) continue;

                    $quantity = (int)($p['quantity'] ?? 0);
                    if ($quantity <= 0) {
                        throw new \InvalidArgumentException('La cantidad a trasladar debe ser mayor que cero.');
                    }

                    $material = $entityManager->getRepository(Material::class)->find($materialId);
                    if (!$material) continue;

                    $unitId = !empty($p['unit_id']) && is_numeric($p['unit_id']) ? (int)$p['unit_id'] : null;
                    $stockId = !empty($p['stock_id']) && is_numeric($p['stock_id']) ? (int)$p['stock_id'] : null;

                    $unitToMove = null;
                    $stockToMove = null;
                    $origin = null;
                    $batch = null;

                    if ($material->getNature() === Material::NATURE_CONSUMABLE) {
                        if (!$stockId) {
                            throw new \RuntimeException(sprintf("Es obligatorio proporcionar un ID de stock origen para el material '%s'. Datos recibidos: %s", $material->getName(), json_encode($p)));
                        }
                        $stockToMove = $entityManager->getRepository(MaterialStock::class)->find($stockId);
                        if (!$stockToMove) {
                            throw new \RuntimeException(sprintf("No se ha encontrado el registro de stock ID %d para el material '%s'.", $stockId, $material->getName()));
                        }
                        $origin = $stockToMove->getLocation();
                        $batch = $stockToMove->getBatch();
                    } else {
                        if (!$unitId) {
                            throw new \RuntimeException(sprintf("Es obligatorio proporcionar un ID de unidad origen para el material '%s'.", $material->getName()));
                        }
                        $unitToMove = $entityManager->getRepository(MaterialUnit::class)->find($unitId);
                        if (!$unitToMove) {
                            throw new \RuntimeException(sprintf("No se ha encontrado la unidad ID %d para el material '%s'.", $unitId, $material->getName()));
                        }
                        $origin = $unitToMove->getLocation();
                    }

                    if (!$origin) {
                        throw new \RuntimeException(sprintf("El registro de stock/unidad origen para '%s' no tiene una ubicación asignada.", $material->getName()));
                    }

                    // Prevenir que el botiquín se proponga a sí mismo (Filtro de Contenedor adicional)
                    if ($origin->getId() === $kitLocation->getId()) {
                        throw new \RuntimeException(sprintf("No se puede trasladar '%s' desde la misma ubicación de destino.", $material->getName()));
                    }

                    $materialManager->transfer(
                        $material,
                        $origin,
                        $kitLocation,
                        $quantity,
                        'Reposición de botiquín ' . ($unit->getAlias() ?: $unit->getSerialNumber()),
                        null,
                        $unitToMove,
                        $batch,
                        $stockToMove
                    );
                }
                $entityManager->flush();
            });
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error al procesar el traslado: ' . $e->getMessage());
            // Log the payload for debugging if it's a critical logic error
            if (str_contains($e->getMessage(), 'Heuristic') || str_contains($e->getMessage(), 'insuficiente') || str_contains($e->getMessage(), 'registro de stock')) {
                $this->addFlash('info', 'DEBUG DATA (Proposals Payload): ' . $proposalsData);
            }
            return $this->redirectToRoute('app_kit_inventory', ['id' => $unit->getId()]);
        }

        $this->addFlash('success', $isNew ? 'Botiquín registrado y cargado correctamente.' : 'Botiquín repuesto correctamente.');

        return $this->redirectToRoute('app_kit_inventory', ['id' => $unit->getId()], Response::HTTP_SEE_OTHER);
    }

    private function finalizeKitCreation(array $draft, EntityManagerInterface $entityManager, MaterialManager $materialManager): MaterialUnit
    {
        $template = $entityManager->getRepository(KitTemplate::class)->find($draft['template_id']);
        
        // 1. Physical Unit deduplication/creation
        $material = $this->getOrCreateKitMaterial(
            $entityManager,
            $template->getName(),
            $draft['brand'] ?? null,
            $draft['model'] ?? null,
            $draft['barcode'] ?? null,
            $draft['subfamily'] ?? null
        );

        // Handle "IVA Incluido" logic: desglosar hacia atrás (Precio / 1.21)
        // Handle "IVA Incluido" logic: reverse calculate base price
        $purchasePrice = (float)$draft['purchase_price'];
        $ivaValue = $draft['iva'] ?: '0.21';
        
        if ($ivaValue === '1.21' || $ivaValue === 'included') {
            $purchasePrice = round($purchasePrice / 1.21, 2);
            $ivaValue = '0.21'; // Store the actual tax rate
        }

        $serial = $draft['serial_number'] ?: null;
        $unit = null;
        if ($serial) {
            $unit = $entityManager->getRepository(MaterialUnit::class)->findOneBy(['serialNumber' => $serial]);
        }

        if ($unit) {
            if ($draft['alias']) $unit->setAlias($draft['alias']);
            $unit->setTemplate($template);
            $unit->setPurchasePrice($purchasePrice);
        } else {
            $unit = $materialManager->createUnit($material, [
                'alias' => $draft['alias'],
                'serialNumber' => $serial,
                'purchasePrice' => $purchasePrice,
            ], $materialManager->getDefaultLocation($material));
            $unit->setTemplate($template);
        }

        // Set new fields
        $unit->setSupplier($draft['supplier']);
        $unit->setIva($ivaValue);
        if ($draft['margin_percentage'] !== null && $draft['margin_percentage'] !== '') {
            $unit->setMarginPercentage($draft['margin_percentage']);
        }

        // 2. Mobile Location
        $location = $unit->getKitLocation();
        if (!$location) {
            $location = new Location();
            $location->setType(Location::TYPE_KIT);
            $location->addMaterialUnit($unit);
            $unit->setKitLocation($location);
            $entityManager->persist($location);
        }
        $location->setName('Botiquín: ' . ($unit->getAlias() ?: $unit->getSerialNumber()));

        $entityManager->persist($unit);
        $entityManager->flush();

        return $unit;
    }

    private function getOrCreateKitMaterial(
        EntityManagerInterface $entityManager,
        string $name = 'Botiquín',
        ?string $brand = null,
        ?string $model = null,
        ?string $barcode = null,
        ?string $subFamily = null
    ): Material {
        $repo = $entityManager->getRepository(Material::class);
        $material = null;

        if ($barcode) {
            $material = $repo->findOneBy(['barcode' => $barcode]);
        }

        if (!$material) {
            $criteria = ['name' => $name];
            if ($brand) $criteria['brandModel'] = $brand . ($model ? ' ' . $model : '');

            $material = $repo->findOneBy($criteria);
        }

        if (!$material) {
            $material = new Material();
            $material->setName($name);
            $material->setBrandModel($brand . ($model ? ' ' . $model : ''));
            $material->setBarcode($barcode);
            $material->setSubFamily($subFamily);
            $material->setCategory('Sanitario'); // This ensures it routes to Pharmacy by default
            $material->setNature(Material::NATURE_TECHNICAL);
            $material->setStock(0);
            $entityManager->persist($material);
            $entityManager->flush();
        }
        return $material;
    }

    private function findAlternativeLocations(Material $material, Location $warehouse, Location $excludeKit, EntityManagerInterface $entityManager): array
    {
        $alternatives = [];

        if ($material->getNature() === Material::NATURE_CONSUMABLE) {
            $qb = $entityManager->getRepository(MaterialStock::class)->createQueryBuilder('ms')
                ->where('ms.material = :material')
                ->andWhere('ms.quantity > 0')
                ->setParameter('material', $material);

            if ($warehouse->getId()) {
                $qb->andWhere('ms.location != :warehouse')
                   ->setParameter('warehouse', $warehouse);
            }

            if ($excludeKit->getId()) {
                $qb->andWhere('ms.location != :kit')
                   ->setParameter('kit', $excludeKit);
            }

            $stocks = $qb->getQuery()->getResult();

            foreach ($stocks as $s) {
                $alternatives[] = [
                    'location' => $s->getLocation()->getName(),
                    'quantity' => $s->getQuantity(),
                    'type' => $s->getLocation()->getType()
                ];
            }
        } else {
            // For technical equipment, find units in other locations (Kits/Vehicles)
            $qb = $entityManager->getRepository(MaterialUnit::class)->createQueryBuilder('u')
                ->join('u.location', 'l')
                ->where('u.material = :material')
                ->andWhere('l.type != :warehouseType')
                ->setParameter('material', $material)
                ->setParameter('warehouseType', Location::TYPE_WAREHOUSE);

            if ($excludeKit->getId()) {
                $qb->andWhere('l != :excludeKit')
                   ->setParameter('excludeKit', $excludeKit);
            }

            $units = $qb->getQuery()->getResult();

            // Group by location
            $grouped = [];
            foreach ($units as $u) {
                $locName = $u->getLocation()->getName();
                if (!isset($grouped[$locName])) {
                    $grouped[$locName] = [
                        'location' => $locName,
                        'quantity' => 0,
                        'type' => $u->getLocation()->getType()
                    ];
                }
                $grouped[$locName]['quantity']++;
            }
            $alternatives = array_values($grouped);
        }

        return $alternatives;
    }
}
