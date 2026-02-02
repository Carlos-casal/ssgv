<?php

namespace App\Controller;

use App\Entity\Material;
use App\Entity\MaterialUnit;
use App\Form\MaterialType;
use App\Form\MaterialUnitType;
use App\Repository\MaterialRepository;
use App\Repository\MaterialUnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/material')]
class MaterialController extends AbstractController
{
    #[Route('/', name: 'app_material_index', methods: ['GET'])]
    public function index(Request $request, MaterialRepository $materialRepository): Response
    {
        $category = $request->query->get('category');
        if ($category) {
            $materials = $materialRepository->findBy(['category' => $category]);
        } else {
            $materials = $materialRepository->findAll();
        }

        return $this->render('material/index.html.twig', [
            'materials' => $materials,
            'current_category' => $category,
            'current_section' => 'recursos'
        ]);
    }

    #[Route('/new', name: 'app_material_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
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
            $entityManager->persist($material);
            $entityManager->flush();

            return $this->redirectToRoute('app_material_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('material/new.html.twig', [
            'material' => $material,
            'form' => $form,
            'current_section' => 'recursos'
        ]);
    }

    #[Route('/{id}', name: 'app_material_show', methods: ['GET'])]
    public function show(Material $material): Response
    {
        return $this->render('material/show.html.twig', [
            'material' => $material,
            'current_section' => 'recursos'
        ]);
    }

    #[Route('/{id}/edit', name: 'app_material_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Material $material, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

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
}
