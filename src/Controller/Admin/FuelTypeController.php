<?php

namespace App\Controller\Admin;

use App\Entity\FuelType;
use App\Form\FuelTypeType;
use App\Repository\FuelTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for managing fuel types in the admin panel.
 */
#[Route('/admin/fuel-types')]
class FuelTypeController extends AbstractController
{
    /**
     * Displays a list of all fuel types.
     *
     * @param FuelTypeRepository $fuelTypeRepository The repository for fuel types.
     * @return Response The response object.
     */
    #[Route('/', name: 'app_admin_fuel_type_index', methods: ['GET'])]
    public function index(FuelTypeRepository $fuelTypeRepository): Response
    {
        return $this->render('admin/fuel_type/index.html.twig', [
            'fuel_types' => $fuelTypeRepository->findAll(),
        ]);
    }

    /**
     * Creates a new fuel type.
     *
     * @param Request $request The request object.
     * @param EntityManagerInterface $entityManager The entity manager.
     * @return Response The response object.
     */
    #[Route('/new', name: 'app_admin_fuel_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fuelType = new FuelType();
        $form = $this->createForm(FuelTypeType::class, $fuelType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fuelType);
            $entityManager->flush();

            $this->addFlash('success', 'Tipo de combustible creado con éxito.');

            return $this->redirectToRoute('app_admin_fuel_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/fuel_type/new.html.twig', [
            'fuel_type' => $fuelType,
            'form' => $form,
        ]);
    }

    /**
     * Edits an existing fuel type.
     *
     * @param Request $request The request object.
     * @param FuelType $fuelType The fuel type to edit.
     * @param EntityManagerInterface $entityManager The entity manager.
     * @return Response The response object.
     */
    #[Route('/{id}/edit', name: 'app_admin_fuel_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FuelType $fuelType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FuelTypeType::class, $fuelType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Tipo de combustible actualizado con éxito.');

            return $this->redirectToRoute('app_admin_fuel_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/fuel_type/edit.html.twig', [
            'fuel_type' => $fuelType,
            'form' => $form,
        ]);
    }

    /**
     * Deletes a fuel type.
     *
     * @param Request $request The request object.
     * @param FuelType $fuelType The fuel type to delete.
     * @param EntityManagerInterface $entityManager The entity manager.
     * @return Response The response object.
     */
    #[Route('/{id}', name: 'app_admin_fuel_type_delete', methods: ['POST'])]
    public function delete(Request $request, FuelType $fuelType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fuelType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fuelType);
            $entityManager->flush();

            $this->addFlash('success', 'Tipo de combustible eliminado con éxito.');
        }

        return $this->redirectToRoute('app_admin_fuel_type_index', [], Response::HTTP_SEE_OTHER);
    }
}