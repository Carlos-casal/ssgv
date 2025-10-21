<?php

namespace App\Controller\Admin;

use App\Entity\Battery;
use App\Entity\Talkie;
use App\Form\BatteryType;
use App\Form\TalkieType;
use App\Repository\BatteryRepository;
use App\Repository\TalkieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/recursos/comunicaciones')]
class CommunicationsController extends AbstractController
{
    #[Route('/', name: 'app_admin_communications_index', methods: ['GET'])]
    public function index(TalkieRepository $talkieRepository, BatteryRepository $batteryRepository): Response
    {
        return $this->render('admin/communications/index.html.twig', [
            'talkies' => $talkieRepository->findAll(),
            'batteries' => $batteryRepository->findAll(),
        ]);
    }

    #[Route('/talkie/new', name: 'app_admin_talkie_new', methods: ['GET', 'POST'])]
    public function newTalkie(Request $request, EntityManagerInterface $entityManager): Response
    {
        $talkie = new Talkie();
        $form = $this->createForm(TalkieType::class, $talkie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($talkie);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/communications/talkie/new.html.twig', [
            'talkie' => $talkie,
            'form' => $form,
        ]);
    }

    #[Route('/talkie/{id}/edit', name: 'app_admin_talkie_edit', methods: ['GET', 'POST'])]
    public function editTalkie(Request $request, Talkie $talkie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TalkieType::class, $talkie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/communications/talkie/edit.html.twig', [
            'talkie' => $talkie,
            'form' => $form,
        ]);
    }

    #[Route('/talkie/{id}', name: 'app_admin_talkie_delete', methods: ['POST'])]
    public function deleteTalkie(Request $request, Talkie $talkie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$talkie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($talkie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/battery/new', name: 'app_admin_battery_new', methods: ['GET', 'POST'])]
    public function newBattery(Request $request, EntityManagerInterface $entityManager): Response
    {
        $battery = new Battery();
        $form = $this->createForm(BatteryType::class, $battery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($battery);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/communications/battery/new.html.twig', [
            'battery' => $battery,
            'form' => $form,
        ]);
    }

    #[Route('/battery/{id}/edit', name: 'app_admin_battery_edit', methods: ['GET', 'POST'])]
    public function editBattery(Request $request, Battery $battery, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BatteryType::class, $battery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/communications/battery/edit.html.twig', [
            'battery' => $battery,
            'form' => $form,
        ]);
    }

    #[Route('/battery/{id}', name: 'app_admin_battery_delete', methods: ['POST'])]
    public function deleteBattery(Request $request, Battery $battery, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$battery->getId(), $request->request->get('_token'))) {
            $entityManager->remove($battery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
    }
}
