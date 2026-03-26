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

        return $this->render('kit/index.html.twig', [
            'kits' => $kits,
        ]);
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

        return $this->render('kit/template_index.html.twig', [
            'templates' => $templates,
        ]);
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

        return $this->render('kit/template_new.html.twig', [
            'materials' => $materials,
        ]);
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

        return $this->render('kit/template_edit.html.twig', [
            'template' => $template,
            'materials' => $materials,
        ]);
    }

    #[Route('/new', name: 'app_kit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('kit_new', $request->request->get('_token'))) {
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

            // 1. Create the physical unit
            $material = $entityManager->getRepository(Material::class)->findOneBy(['name' => 'Botiquín']);
            if (!$material) {
                $material = $entityManager->getRepository(Material::class)->findOneBy(['category' => 'Sanitario', 'nature' => Material::NATURE_TECHNICAL]);
            }
            if (!$material) {
                $material = $entityManager->getRepository(Material::class)->findOneBy(['category' => 'Sanitario']);
            }
            if (!$material) {
                $material = $entityManager->getRepository(Material::class)->findOneBy([]);
            }

            if (!$material) {
                throw new \Exception("No se ha encontrado ningún Material en la base de datos para asignar al Botiquín.");
            }

            $unit = new MaterialUnit();
            $unit->setMaterial($material);
            $unit->setAlias($alias);
            $unit->setSerialNumber($serialNumber);
            $unit->setTemplate($template);
            $unit->setOperationalStatus('OPERATIVO');

            // 2. Create the mobile location
            $location = new Location();
            $location->setName('Botiquín: ' . ($alias ?: $serialNumber));
            $location->setType(Location::TYPE_KIT);
            $location->setMaterialUnit($unit);
            $unit->setKitLocation($location);

            $entityManager->persist($unit);
            $entityManager->persist($location);
            $entityManager->flush();

            $this->addFlash('success', 'Botiquín "' . ($alias ?: 'Sin Alias') . '" registrado correctamente.');

            // Redirect directly to refill preview
            return $this->redirectToRoute('app_kit_refill_preview', ['id' => $unit->getId()]);
        }

        return $this->render('kit/new.html.twig', [
            'templates' => $entityManager->getRepository(KitTemplate::class)->findAll(),
        ]);
    }

    #[Route('/{id}/inventory', name: 'app_kit_inventory', methods: ['GET'])]
    public function inventory(MaterialUnit $unit): Response
    {
        if (!$unit->getTemplate()) {
            throw $this->createNotFoundException('Este material no es un botiquín.');
        }

        return $this->render('kit/inventory.html.twig', [
            'unit' => $unit,
        ]);
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
                        null,
                        $location
                    );
                }
            }

            $this->addFlash('success', 'Consumo registrado correctamente.');
            return $this->redirectToRoute('app_kit_inventory', ['id' => $unit->getId()]);
        }

        return $this->render('kit/consume.html.twig', [
            'unit' => $unit,
        ]);
    }

    #[Route('/{id}/refill', name: 'app_kit_refill', methods: ['POST'])]
    public function refill(MaterialUnit $unit): Response
    {
        return $this->redirectToRoute('app_kit_refill_preview', ['id' => $unit->getId()]);
    }

    #[Route('/{id}/refill/preview', name: 'app_kit_refill_preview', methods: ['GET'])]
    public function refillPreview(MaterialUnit $unit, MaterialManager $materialManager, EntityManagerInterface $entityManager): Response
    {
        $template = $unit->getTemplate();
        if (!$template) {
            throw $this->createNotFoundException('Este botiquín no tiene una plantilla asignada.');
        }

        $kitLocation = $unit->getKitLocation();
        $centralWarehouse = $materialManager->getCentralWarehouse();

        $proposals = [];
        $shortages = [];
        $warehouseOptions = []; // To store all available batches/units for each material

        foreach ($template->getItems() as $item) {
            $material = $item->getMaterial();

            // Exclude ONLY the material assigned to the physical unit (the container itself)
            if ($material->getId() === $unit->getMaterial()->getId()) {
                continue;
            }

            $idealQty = $item->getQuantity();

            // Calculate current stock in the kit correctly
            $currentQty = 0;
            if ($material->getNature() === Material::NATURE_CONSUMABLE) {
                $stocks = $entityManager->getRepository(MaterialStock::class)->findBy([
                    'material' => $material,
                    'location' => $kitLocation
                ]);
                foreach ($stocks as $s) $currentQty += $s->getQuantity();
            } else {
                // For Technical, count physical units assigned to this location
                $currentQty = $entityManager->getRepository(MaterialUnit::class)->count([
                    'material' => $material,
                    'location' => $kitLocation
                ]);
            }

            $needed = $idealQty - $currentQty;
            if ($needed <= 0) continue;

            $options = [];
            $availableInWarehouse = 0;

            if ($material->getNature() === Material::NATURE_CONSUMABLE) {
                $stocksInWarehouse = $entityManager->getRepository(MaterialStock::class)->createQueryBuilder('ms')
                    ->leftJoin('ms.batch', 'b')
                    ->where('ms.material = :material')
                    ->andWhere('ms.location = :location')
                    ->andWhere('ms.quantity > 0')
                    ->setParameter('material', $material)
                    ->setParameter('location', $centralWarehouse)
                    ->orderBy('b.expirationDate', 'ASC')
                    ->addOrderBy('b.createdAt', 'ASC')
                    ->getQuery()
                    ->getResult();

                foreach ($stocksInWarehouse as $stock) {
                    $options[] = [
                        'id' => $stock->getBatch() ? $stock->getBatch()->getId() : 'NO_BATCH',
                        'label' => $stock->getBatch() ? 'Lote: ' . $stock->getBatch()->getBatchNumber() . ' (Exp: ' . ($stock->getBatch()->getExpirationDate() ? $stock->getBatch()->getExpirationDate()->format('d/m/Y') : 'N/A') . ')' : 'Sin Lote',
                        'available' => $stock->getQuantity()
                    ];
                    $availableInWarehouse += $stock->getQuantity();
                }

                // Initial FIFO proposal based on options
                $remainingNeeded = $needed;
                foreach ($stocksInWarehouse as $stock) {
                    if ($remainingNeeded <= 0) break;
                    $take = min($remainingNeeded, $stock->getQuantity());
                    $proposals[] = [
                        'material' => $material,
                        'quantity' => $take,
                        'origin' => $centralWarehouse,
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
            } else {
                // Technical Equipment - Get ALL units but identify those in other kits
                // Order: location_id ASC (NULLS FIRST in many DBs, but let's be explicit with CASE or just sort in PHP if needed)
                // We'll use id ASC as a proxy for FIFO (registration date)
                $allUnits = $entityManager->getRepository(MaterialUnit::class)->createQueryBuilder('u')
                    ->leftJoin('u.location', 'l')
                    ->where('u.material = :material')
                    ->andWhere('u.operationalStatus = :status')
                    ->setParameter('material', $material)
                    ->setParameter('status', 'OPERATIVO')
                    ->orderBy('u.location', 'ASC') // NULLs (unassigned) first in many SQL dialects
                    ->addOrderBy('u.id', 'ASC')
                    ->getQuery()
                    ->getResult();

                foreach ($allUnits as $u) {
                    $isBusy = ($u->getLocation() && $u->getLocation()->getType() === Location::TYPE_KIT && $u->getLocation() !== $kitLocation);
                    $label = $u->getAlias() ?: ($u->getSerialNumber() ?: 'Unidad ' . $u->getId());

                    $options[] = [
                        'id' => $u->getId(),
                        'label' => $label,
                        'available' => 1,
                        'busy' => $isBusy,
                        'locationName' => $u->getLocation() ? $u->getLocation()->getName() : 'Sin asignar'
                    ];
                    if ($u->getLocation() === $centralWarehouse || !$u->getLocation()) {
                        $availableInWarehouse += 1;
                    }
                }

                $unitsInWarehouse = array_filter($allUnits, fn($u) => $u->getLocation() === $centralWarehouse);
                $unitsInWarehouse = array_values($unitsInWarehouse);

                // Initial FIFO proposal
                for ($i = 0; $i < min($needed, count($unitsInWarehouse)); $i++) {
                    $proposals[] = [
                        'material' => $material,
                        'quantity' => 1,
                        'origin' => $centralWarehouse,
                        'batch' => null,
                        'unit' => $unitsInWarehouse[$i]
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
                if ($needed > count($unitsInWarehouse)) {
                    for ($i = 0; $i < ($needed - count($unitsInWarehouse)); $i++) {
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
            $warehouseOptions[$material->getId()] = $options;
        }

        return $this->render('kit/refill_preview.html.twig', [
            'unit' => $unit,
            'proposals' => $proposals,
            'shortages' => $shortages,
            'warehouseOptions' => $warehouseOptions,
            'centralWarehouse' => $centralWarehouse
        ]);
    }

    #[Route('/{id}/delete', name: 'app_kit_delete', methods: ['POST'])]
    public function deleteKit(Request $request, MaterialUnit $unit, EntityManagerInterface $entityManager, MaterialManager $materialManager): Response
    {
        if (!$this->isCsrfTokenValid('delete_kit_' . $unit->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF inválido.');
        }

        $location = $unit->getKitLocation();
        if ($location) {
            // 1. Manually nullify MaterialMovement references to this location (destinations or origins)
            // This is a safety measure if SET NULL is not correctly cascaded at DB level
            $entityManager->createQueryBuilder()
                ->update(\App\Entity\MaterialMovement::class, 'm')
                ->set('m.origin', 'NULL')
                ->where('m.origin = :loc')
                ->setParameter('loc', $location)
                ->getQuery()
                ->execute();

            $entityManager->createQueryBuilder()
                ->update(\App\Entity\MaterialMovement::class, 'm')
                ->set('m.destination', 'NULL')
                ->where('m.destination = :loc')
                ->setParameter('loc', $location)
                ->getQuery()
                ->execute();

            // 2. Clean up associated stocks and technical units inside the kit
            foreach ($location->getStocks() as $stock) {
                $entityManager->remove($stock);
            }

            foreach ($location->getUnits() as $otherUnit) {
                // Return units to central warehouse
                $otherUnit->setLocation($materialManager->getCentralWarehouse());
            }

            // Flush changes before deleting the location to clear references
            $entityManager->flush();

            // 3. Break the circular reference: Unit -> Location -> Unit
            $unit->setKitLocation(null);
            $entityManager->flush();

            // 4. Remove the Location entity
            $entityManager->remove($location);
            $entityManager->flush();
        }

        // 5. Finaly remove the Kit Unit
        $entityManager->remove($unit);
        $entityManager->flush();

        $this->addFlash('success', 'Botiquín eliminado correctamente.');

        return $this->redirectToRoute('app_kit_index');
    }

    #[Route('/{id}/refill/confirm', name: 'app_kit_refill_confirm', methods: ['POST'])]
    public function refillConfirm(Request $request, MaterialUnit $unit, MaterialManager $materialManager, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('kit_refill_confirm', $request->request->get('_token'))) {
            return new Response('Token CSRF inválido.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $proposalsData = $request->request->get('proposals_data');
        $proposals = $proposalsData ? json_decode($proposalsData, true) : [];
        $kitLocation = $unit->getKitLocation();

        if (empty($proposals)) {
            $this->addFlash('warning', 'No se han recibido datos de la propuesta.');
            return $this->redirectToRoute('app_kit_inventory', ['id' => $unit->getId()]);
        }

        foreach ($proposals as $p) {
            $material = $entityManager->getRepository(Material::class)->find($p['material_id']);
            $batch = !empty($p['batch_id']) ? $entityManager->getRepository(\App\Entity\MaterialBatch::class)->find($p['batch_id']) : null;
            $unitToMove = !empty($p['unit_id']) ? $entityManager->getRepository(MaterialUnit::class)->find($p['unit_id']) : null;

            // Determine origin dynamically if it was moved in the UI (occupied units)
            $origin = $entityManager->getRepository(Location::class)->find($p['origin_id']);
            if ($unitToMove && $unitToMove->getLocation()) {
                $origin = $unitToMove->getLocation();
            }

            $materialManager->transfer(
                $material,
                $origin,
                $kitLocation,
                (int)$p['quantity'],
                'Reposición de botiquín ' . $unit->getAlias(),
                null,
                'UNICA',
                $unitToMove,
                $batch
            );
        }

        $this->addFlash('success', 'Botiquín repuesto correctamente.');
        return $this->redirectToRoute('app_kit_inventory', ['id' => $unit->getId()]);
    }

    private function findAlternativeLocations(Material $material, Location $warehouse, Location $excludeKit, EntityManagerInterface $entityManager): array
    {
        $stocks = $entityManager->getRepository(MaterialStock::class)->createQueryBuilder('ms')
            ->where('ms.material = :material')
            ->andWhere('ms.location != :warehouse')
            ->andWhere('ms.location != :kit')
            ->andWhere('ms.quantity > 0')
            ->setParameter('material', $material)
            ->setParameter('warehouse', $warehouse)
            ->setParameter('kit', $excludeKit)
            ->getQuery()
            ->getResult();

        $alternatives = [];
        foreach ($stocks as $s) {
            $alternatives[] = [
                'location' => $s->getLocation()->getName(),
                'quantity' => $s->getQuantity(),
                'type' => $s->getLocation()->getType()
            ];
        }
        return $alternatives;
    }
}
