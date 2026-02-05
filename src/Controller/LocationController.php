<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\LocationReview;
use App\Form\LocationType;
use App\Form\LocationReviewType;
use App\Repository\LocationRepository;
use App\Repository\LocationReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/warehouse/locations')]
class LocationController extends AbstractController
{
    #[Route('/', name: 'app_location_index', methods: ['GET'])]
    public function index(LocationRepository $locationRepository): Response
    {
        return $this->render('location/index.html.twig', [
            'locations' => $locationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_location_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($location);
            $entityManager->flush();

            $this->addFlash('success', 'Ubicación creada correctamente.');

            return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('location/new.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_location_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Location $location, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Ubicación actualizada correctamente.');

            return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('location/edit.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }

    #[Route('/reviews', name: 'app_location_review_index', methods: ['GET'])]
    public function reviewIndex(LocationReviewRepository $reviewRepository): Response
    {
        return $this->render('location/review_index.html.twig', [
            'reviews' => $reviewRepository->findBy([], ['reviewDate' => 'DESC']),
        ]);
    }

    #[Route('/reviews/new', name: 'app_location_review_new', methods: ['GET', 'POST'])]
    public function reviewNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $review = new LocationReview();
        $form = $this->createForm(LocationReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash('success', 'Revisión registrada correctamente.');

            return $this->redirectToRoute('app_location_review_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('location/review_new.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }
}
