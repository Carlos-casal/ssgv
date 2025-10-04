<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Controller for managing vehicles in the admin panel.
 */
#[Route('/admin/vehicles')]
class VehicleController extends AbstractController
{
    /**
     * Displays a list of all vehicles.
     *
     * @param VehicleRepository $vehicleRepository The repository for vehicles.
     * @return Response The response object.
     */
    #[Route('/', name: 'app_vehicle_index', methods: ['GET'])]
    public function index(VehicleRepository $vehicleRepository): Response
    {
        return $this->render('vehicle/index.html.twig', [
            'vehicles' => $vehicleRepository->findAll(),
        ]);
    }

    /**
     * Creates a new vehicle.
     * Handles photo uploads for the vehicle.
     *
     * @param Request $request The request object.
     * @param EntityManagerInterface $entityManager The entity manager.
     * @param SluggerInterface $slugger The slugger to create safe filenames.
     * @return Response The response object.
     */
    #[Route('/new', name: 'app_vehicle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $vehicle = new Vehicle();
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('vehicle_photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $vehicle->setPhoto($newFilename);
            }

            $entityManager->persist($vehicle);
            $entityManager->flush();

            $this->addFlash('success', 'Vehículo creado con éxito.');

            return $this->redirectToRoute('app_vehicle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicle/new.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays the details of a specific vehicle.
     *
     * @param Vehicle $vehicle The vehicle to display.
     * @return Response The response object.
     */
    #[Route('/{id}', name: 'app_vehicle_show', methods: ['GET'])]
    public function show(Vehicle $vehicle): Response
    {
        return $this->render('vehicle/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * Edits an existing vehicle.
     * Handles photo updates, including deleting the old photo.
     *
     * @param Request $request The request object.
     * @param Vehicle $vehicle The vehicle to edit.
     * @param EntityManagerInterface $entityManager The entity manager.
     * @param SluggerInterface $slugger The slugger to create safe filenames.
     * @param Filesystem $filesystem The filesystem component to delete files.
     * @return Response The response object.
     */
    #[Route('/{id}/edit', name: 'app_vehicle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager, SluggerInterface $slugger, Filesystem $filesystem): Response
    {
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                // Delete old photo if it exists
                $oldPhoto = $vehicle->getPhoto();
                if ($oldPhoto) {
                    $filesystem->remove($this->getParameter('vehicle_photos_directory').'/'.$oldPhoto);
                }

                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('vehicle_photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception
                }

                $vehicle->setPhoto($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Vehículo actualizado con éxito.');

            return $this->redirectToRoute('app_vehicle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicle/edit.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a vehicle.
     * Also deletes the associated photo from the filesystem.
     *
     * @param Request $request The request object.
     * @param Vehicle $vehicle The vehicle to delete.
     * @param EntityManagerInterface $entityManager The entity manager.
     * @param Filesystem $filesystem The filesystem component to delete files.
     * @return Response The response object.
     */
    #[Route('/{id}', name: 'app_vehicle_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager, Filesystem $filesystem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicle->getId(), $request->request->get('_token'))) {
            $photo = $vehicle->getPhoto();
            if ($photo) {
                $filesystem->remove($this->getParameter('vehicle_photos_directory').'/'.$photo);
            }

            $entityManager->remove($vehicle);
            $entityManager->flush();

            $this->addFlash('success', 'Vehículo eliminado con éxito.');
        }

        return $this->redirectToRoute('app_vehicle_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Toggles the "out of service" status of a vehicle.
     *
     * @param Request $request The request object.
     * @param Vehicle $vehicle The vehicle to update.
     * @param EntityManagerInterface $entityManager The entity manager.
     * @return Response The response object.
     */
    #[Route('/{id}/toggle-out-of-service', name: 'app_vehicle_toggle_out_of_service', methods: ['POST'])]
    public function toggleOutOfService(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('toggle'.$vehicle->getId(), $request->request->get('_token'))) {
            $isOutOfService = $request->request->has('isOutOfService');
            $vehicle->setOutOfService($isOutOfService);
            $entityManager->flush();

            $this->addFlash('success', 'El estado del vehículo ha sido actualizado.');
        }

        return $this->redirectToRoute('app_vehicle_index', [], Response::HTTP_SEE_OTHER);
    }
}