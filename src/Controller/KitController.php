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

    #[Route('/templates/seed-defaults', name: 'app_kit_template_seed_defaults', methods: ['POST'])]
    public function seedDefaultTemplates(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('seed_defaults', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF inválido.');
        }

        $defaults = [
            ['name' => 'Mochila SVB Básica', 'type' => 'Mochila'],
            ['name' => 'Maletín de Oxigenoterapia', 'type' => 'Bolsa'],
            ['name' => 'Riñonera de Intervención Rápida', 'type' => 'Riñonera'],
        ];

        foreach ($defaults as $data) {
            $existing = $entityManager->getRepository(KitTemplate::class)->findOneBy(['name' => $data['name']]);
            if (!$existing) {
                $template = new KitTemplate();
                $template->setName($data['name']);
                $template->setContainerType($data['type']);
                $entityManager->persist($template);
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
                if (empty($itemData['material']) || empty($itemData['quantity'])) continue;

                $material = $entityManager->getRepository(Material::class)->find($itemData['material']);
                if (!$material) continue;

                $item = new KitTemplateItem();
                $item->setMaterial($material);
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
                if (empty($itemData['material']) || empty($itemData['quantity'])) continue;

                $item = new KitTemplateItem();
                $item->setMaterial($entityManager->getReference(Material::class, $itemData['material']));
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
        $centralWarehouse = $materialManager->getCentralWarehouse();
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
        
        // Use 'Botiquín' material for the dummy unit
        $unit->setMaterial($this->getOrCreateKitMaterial($entityManager));

        $proposals = [];
        $shortages = [];
        $warehouseOptions = [];

        // 1. Proposals for items IN THE TEMPLATE (FIFO)
        $dummyLocation = new Location(); // Not persisted

        foreach ($template->getItems() as $item) {
            $material = $item->getMaterial();
            $defaultWarehouse = $materialManager->getDefaultLocation($material);
            $this->addMaterialOptionsToRefill($material, $unit, $dummyLocation, $defaultWarehouse, $proposals, $shortages, $warehouseOptions, $entityManager, $item->getQuantity());
        }

        // 2. Proposals for ALL OTHER materials in inventory
        $allMaterials = $entityManager->getRepository(Material::class)->findAll();
        foreach ($allMaterials as $m) {
            if (isset($warehouseOptions[$m->getId()])) continue;
            $defaultWarehouse = $materialManager->getDefaultLocation($m);
            $this->addMaterialOptionsToRefill($m, $unit, $dummyLocation, $defaultWarehouse, $proposals, $shortages, $warehouseOptions, $entityManager, 0, true);
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

        $response = $this->render('kit/refill_preview.html.twig', [
            'unit' => $unit,
            'proposals' => $proposals,
            'shortages' => $shortages,
            'currentContents' => [],
            'warehouseOptions' => $warehouseOptions,
            'centralWarehouse' => $centralWarehouse,
            'is_new' => true,
            'allMaterials' => $allMaterials
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;

    }

    #[Route('/{id}/inventory', name: 'app_kit_inventory', methods: ['GET'])]
    public function inventory(MaterialUnit $unit): Response
    {
        if (!$unit->getTemplate()) {
            throw $this->createNotFoundException('Este material no es un botiquín.');
        }

        $response = $this->render('kit/inventory.html.twig', [
            'unit' => $unit,
        ]);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        return $response;
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
        $centralWarehouse = $materialManager->getCentralWarehouse();
        $template = $unit->getTemplate();
        if (!$template) {
            throw $this->createNotFoundException('Este botiquín no tiene una plantilla asignada.');
        }

        $kitLocation = $unit->getKitLocation();

        $proposals = [];
        $shortages = [];
        $currentContents = [];
        $warehouseOptions = []; // To store all available batches/units for each material

        // 1. Map existing stock in the kit (to avoid "disappearance" on reload/back)
        foreach ($kitLocation->getStocks() as $stock) {
            if ($stock->getQuantity() <= 0) continue;

            // Skip technical equipment in the stock loop as they are handled in the units loop below
            if ($stock->getMaterial()->getNature() === Material::NATURE_TECHNICAL) {
                continue;
            }

            $currentContents[] = [
                'material' => $stock->getMaterial(),
                'quantity' => $stock->getQuantity(),
                'batch' => $stock->getBatch(),
                'unit' => null
            ];
        }
        foreach ($kitLocation->getUnits() as $kitUnit) {
            if ($kitUnit->getId() === $unit->getId()) continue; // Skip container
            $currentContents[] = [
                'material' => $kitUnit->getMaterial(),
                'quantity' => 1,
                'batch' => null,
                'unit' => $kitUnit
            ];
        }

        // 2. Proposals for items IN THE TEMPLATE (FIFO)
        foreach ($template->getItems() as $item) {
            $material = $item->getMaterial();
            $defaultWarehouse = $materialManager->getDefaultLocation($material);
            $this->addMaterialOptionsToRefill($material, $unit, $kitLocation, $defaultWarehouse, $proposals, $shortages, $warehouseOptions, $entityManager, $item->getQuantity());
        }

        // 3. Proposals for ALL OTHER materials in inventory (empty lists for manual addition)
        $allMaterials = $entityManager->getRepository(Material::class)->findAll();
        foreach ($allMaterials as $m) {
            if (isset($warehouseOptions[$m->getId()])) continue; // Skip if already processed for template
            $defaultWarehouse = $materialManager->getDefaultLocation($m);
            $this->addMaterialOptionsToRefill($m, $unit, $kitLocation, $defaultWarehouse, $proposals, $shortages, $warehouseOptions, $entityManager, 0, true);
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

        $response = $this->render('kit/refill_preview.html.twig', [
            'unit' => $unit,
            'proposals' => $proposals,
            'shortages' => $shortages,
            'currentContents' => $currentContents,
            'warehouseOptions' => $warehouseOptions,
            'centralWarehouse' => $centralWarehouse,
            'allMaterials' => $allMaterials
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
        bool $manualOnly = false
    ): void {
        // Calculate current stock in the kit correctly
        $currentQty = 0;
        if ($kitLocation->getId()) {
            if ($material->getNature() === Material::NATURE_CONSUMABLE) {
                $stocks = $entityManager->getRepository(MaterialStock::class)->findBy([
                    'material' => $material,
                    'location' => $kitLocation
                ]);
                foreach ($stocks as $s) $currentQty += $s->getQuantity();
            } else {
                // For Technical, count physical units assigned to this location
                // EXCLUDE the container unit itself from the count of its own contents
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
                $currentQty = (int)$qb->getQuery()->getSingleScalarResult();
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
                $isBusy = ($stock->getLocation() && $stock->getLocation()->getType() !== Location::TYPE_WAREHOUSE);

                // Skip if it is ALREADY in the current kit (avoid duplicates)
                if ($stock->getLocation() === $kitLocation) continue;

                $shouldSelect = false;
                if (!$isBusy && !$foundAutoSelect && $stock->getQuantity() > 0) {
                    $shouldSelect = true;
                    $foundAutoSelect = true;
                }

                $options[] = [
                    'id' => $stock->getBatch() ? $stock->getBatch()->getId() : 'NO_BATCH',
                    'stock_id' => $stock->getId(),
                    'label' => $stock->getBatch() ? 'Lote: ' . $stock->getBatch()->getBatchNumber() . ' (Exp: ' . ($stock->getBatch()->getExpirationDate() ? $stock->getBatch()->getExpirationDate()->format('d/m/Y') : 'N/A') . ')' : 'Sin Lote',
                    'available' => $stock->getQuantity(),
                    'busy' => $isBusy,
                    'selected' => $shouldSelect,
                    'locationName' => $stock->getLocation() ? $stock->getLocation()->getName() : 'Sin asignar',
                    'locationId' => $stock->getLocation() ? $stock->getLocation()->getId() : null
                ];

                if (!$isBusy) {
                    $availableInWarehouse += $stock->getQuantity();
                }
            }

            // Initial FIFO proposal based on warehouse stocks
            if (!$manualOnly) {
                $remainingNeeded = $needed;
                foreach ($stocks as $stock) {
                    if ($remainingNeeded <= 0) break;
                    $take = min($remainingNeeded, $stock->getQuantity());
                    $proposals[] = [
                        'material' => $material,
                        'quantity' => $take,
                        'origin' => $stock->getLocation(),
                        'batch' => $stock->getBatch(),
                        'unit' => null
                    ];
                    $remainingNeeded -= $take;
                }

                if ($remainingNeeded > 0) {
                    $shortages[] = [
                        'material' => $material,
                        'needed' => $remainingNeeded,
                        'alternatives' => $this->findAlternativeLocations($material, $centralWarehouse, $kitLocation, $entityManager)
                    ];

                    // Add a placeholder for the missing quantity so it always appears in the table
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
                // Skip the kit container itself
                if ($unit->getId() && $u->getId() === $unit->getId()) continue;

                // Skip if it is ALREADY in the current kit
                if ($u->getLocation() === $kitLocation) continue;

                $isBusy = ($u->getLocation() === null || $u->getLocation()->getType() !== Location::TYPE_WAREHOUSE);
                $label = $u->getAlias() ?: ($u->getSerialNumber() ?: 'Unidad ' . $u->getId());

                $shouldSelect = false;
                if (!$isBusy && !$foundAutoSelect) {
                    $shouldSelect = true;
                    $foundAutoSelect = true;
                }

                $options[] = [
                    'id' => $u->getId(),
                    'label' => $label,
                    'available' => 1,
                    'busy' => $isBusy,
                    'selected' => $shouldSelect,
                    'locationName' => $u->getLocation() ? $u->getLocation()->getName() : 'Sin ubicación / Sin asignar',
                    'locationId' => $u->getLocation() ? $u->getLocation()->getId() : null
                ];

                if (!$isBusy) {
                    $availableInWarehouse += 1;
                    $warehouseUnits[] = $u;
                }
            }

            if (!$manualOnly) {
                // Initial FIFO proposal from warehouse units
                for ($i = 0; $i < min($needed, count($warehouseUnits)); $i++) {
                    $proposals[] = [
                        'material' => $material,
                        'quantity' => 1,
                        'origin' => $warehouseUnits[$i]->getLocation() ?: $centralWarehouse,
                        'batch' => null,
                        'unit' => $warehouseUnits[$i]
                    ];
                }

                if ($needed > $availableInWarehouse) {
                    $shortages[] = [
                        'material' => $material,
                        'needed' => $needed - $availableInWarehouse,
                        'alternatives' => $this->findAlternativeLocations($material, $centralWarehouse, $kitLocation, $entityManager)
                    ];
                }

                // If no unit was available in warehouse for some of the needed ones, add placeholders
                if ($needed > count($warehouseUnits)) {
                    for ($i = 0; $i < ($needed - count($warehouseUnits)); $i++) {
                        $proposals[] = [
                            'material' => $material,
                            'quantity' => 1,
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
                        $stock->getBatch()
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

        try {
            $entityManager->wrapInTransaction(function() use ($proposals, $entityManager, $materialManager, $kitLocation, $unit) {
                foreach ($proposals as $p) {
                    $material = $entityManager->getRepository(Material::class)->find($p['material_id']);
                    $batch = !empty($p['batch_id']) ? $entityManager->getRepository(\App\Entity\MaterialBatch::class)->find($p['batch_id']) : null;
                    $unitToMove = !empty($p['unit_id']) ? $entityManager->getRepository(MaterialUnit::class)->find($p['unit_id']) : null;
                    $stockToMove = !empty($p['stock_id']) ? $entityManager->getRepository(MaterialStock::class)->find($p['stock_id']) : null;

                    $originId = $p['origin_id'] ?? null;
                    $origin = $originId ? $entityManager->getRepository(Location::class)->find($originId) : null;

                    if ($unitToMove && $unitToMove->getLocation()) {
                        $origin = $unitToMove->getLocation();
                    }

                    if ($stockToMove && $stockToMove->getLocation()) {
                        $origin = $stockToMove->getLocation();
                        $batch = $stockToMove->getBatch(); // Ensure correct batch from stock
                    }

                    if (!$origin) {
                        $origin = $materialManager->getCentralWarehouse();
                    }

                    $materialManager->transfer(
                        $material,
                        $origin,
                        $kitLocation,
                        (int)$p['quantity'],
                        'Reposición de botiquín ' . ($unit->getAlias() ?: $unit->getSerialNumber()),
                        null,
                        $unitToMove,
                        $batch
                    );
                }
                $entityManager->flush();
            });
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error al procesar el traslado: ' . $e->getMessage());
            return $this->redirectToRoute('app_kit_inventory', ['id' => $unit->getId()]);
        }

        $this->addFlash('success', $isNew ? 'Botiquín registrado y cargado correctamente.' : 'Botiquín repuesto correctamente.');

        return $this->redirectToRoute('app_kit_inventory', ['id' => $unit->getId()], Response::HTTP_SEE_OTHER);
    }

    private function finalizeKitCreation(array $draft, EntityManagerInterface $entityManager, MaterialManager $materialManager): MaterialUnit
    {
        $template = $entityManager->getRepository(KitTemplate::class)->find($draft['template_id']);
        
        // 1. Physical Unit deduplication/creation
        $material = $this->getOrCreateKitMaterial($entityManager);

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

    private function getOrCreateKitMaterial(EntityManagerInterface $entityManager): Material
    {
        $material = $entityManager->getRepository(Material::class)->findOneBy(['name' => 'Botiquín']);
        if (!$material) {
            $material = new Material();
            $material->setName('Botiquín');
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
