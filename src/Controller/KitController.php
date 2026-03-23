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
        $kits = $unitRepository->createQueryBuilder('u')
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
        $templates = $templateRepository->findAll();

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

                $item = new KitTemplateItem();
                $item->setMaterial($entityManager->getReference(Material::class, $itemData['material']));
                $item->setQuantity((int)$itemData['quantity']);
                $template->addItem($item);
            }

            $entityManager->persist($template);
            $entityManager->flush();

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
    public function new(Request $request, EntityManagerInterface $entityManager, MaterialManager $materialManager): Response
    {
        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('kit_new', $request->request->get('_token'))) {
                throw $this->createAccessDeniedException('Token CSRF inválido.');
            }
            $templateId = $request->request->get('template_id');
            $alias = $request->request->get('alias');
            $serialNumber = $request->request->get('serial_number');

            $template = $entityManager->getRepository(KitTemplate::class)->find($templateId);

            // 1. Create the physical unit
            $material = $entityManager->getRepository(Material::class)->findOneBy(['name' => 'Botiquín'])
                        ?? $entityManager->getRepository(Material::class)->findOneBy(['category' => 'Sanitario', 'nature' => Material::NATURE_TECHNICAL]);

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

            // 3. Transfer stock from central warehouse to the kit based on template
            $centralWarehouse = $materialManager->getCentralWarehouse();
            foreach ($template->getItems() as $item) {
                $materialManager->transfer(
                    $item->getMaterial(),
                    $centralWarehouse,
                    $location,
                    $item->getQuantity(),
                    'Carga inicial de botiquín',
                    null
                );
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_kit_index');
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
    public function refill(Request $request, MaterialUnit $unit, MaterialManager $materialManager, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('kit_refill', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF inválido.');
        }
        $template = $unit->getTemplate();
        $location = $unit->getKitLocation();
        $centralWarehouse = $materialManager->getCentralWarehouse();

        foreach ($template->getItems() as $item) {
            $material = $item->getMaterial();
            $targetQty = $item->getQuantity();

            $currentStock = $entityManager->getRepository(MaterialStock::class)->findOneBy([
                'material' => $material,
                'location' => $location
            ]);

            $currentQty = $currentStock ? $currentStock->getQuantity() : 0;
            $diff = $targetQty - $currentQty;

            if ($diff > 0) {
                $materialManager->transfer(
                    $material,
                    $centralWarehouse,
                    $location,
                    $diff,
                    'Reposición de botiquín ' . $unit->getAlias(),
                    null
                );
            }
        }

        $this->addFlash('success', 'Botiquín repuesto según su plantilla.');
        return $this->redirectToRoute('app_kit_inventory', ['id' => $unit->getId()]);
    }
}
