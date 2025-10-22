<?php

namespace App\Controller\Admin;

use App\Entity\Battery;
use App\Entity\Mobile;
use App\Entity\PhoneModel;
use App\Entity\Ptt;
use App\Entity\Talkie;
use App\Form\BatteryType;
use App\Form\MobileType;
use App\Form\PhoneModelType;
use App\Form\PttType;
use App\Form\TalkieType;
use App\Repository\BatteryRepository;
use App\Repository\MobileRepository;
use App\Repository\PhoneModelRepository;
use App\Repository\PttRepository;
use App\Repository\TalkieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/communications')]
class CommunicationsController extends AbstractController
{
    #[Route('/', name: 'app_admin_communications_index', methods: ['GET'])]
    public function index(
        TalkieRepository $talkieRepository,
        BatteryRepository $batteryRepository,
        PttRepository $pttRepository,
        MobileRepository $mobileRepository,
        PhoneModelRepository $phoneModelRepository
    ): Response {
        return $this->render('admin/communications/index.html.twig', [
            'talkies' => $talkieRepository->findAll(),
            'batteries' => $batteryRepository->findAll(),
            'ptts' => $pttRepository->findAll(),
            'mobiles' => $mobileRepository->findAll(),
            'phone_models' => $phoneModelRepository->findAll(),
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

            $this->addFlash('success', 'Talkie creado con éxito.');

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

            $this->addFlash('success', 'Talkie actualizado con éxito.');

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

            $this->addFlash('success', 'Talkie eliminado con éxito.');
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

            $this->addFlash('success', 'Batería creada con éxito.');

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

            $this->addFlash('success', 'Batería actualizada con éxito.');

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

            $this->addFlash('success', 'Batería eliminada con éxito.');
        }

        return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/ptt/new', name: 'app_admin_ptt_new', methods: ['GET', 'POST'])]
    public function newPtt(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ptt = new Ptt();
        $form = $this->createForm(PttType::class, $ptt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ptt);
            $entityManager->flush();

            $this->addFlash('success', 'PTT creado con éxito.');

            return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/communications/ptt/new.html.twig', [
            'ptt' => $ptt,
            'form' => $form,
        ]);
    }

    #[Route('/ptt/{id}/edit', name: 'app_admin_ptt_edit', methods: ['GET', 'POST'])]
    public function editPtt(Request $request, Ptt $ptt, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PttType::class, $ptt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'PTT actualizado con éxito.');

            return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/communications/ptt/edit.html.twig', [
            'ptt' => $ptt,
            'form' => $form,
        ]);
    }

    #[Route('/ptt/{id}', name: 'app_admin_ptt_delete', methods: ['POST'])]
    public function deletePtt(Request $request, Ptt $ptt, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ptt->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ptt);
            $entityManager->flush();

            $this->addFlash('success', 'PTT eliminado con éxito.');
        }

        return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/mobile/new', name: 'app_admin_mobile_new', methods: ['GET', 'POST'])]
    public function newMobile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mobile = new Mobile();
        $form = $this->createForm(MobileType::class, $mobile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mobile);
            $entityManager->flush();

            $this->addFlash('success', 'Móvil creado con éxito.');

            return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/communications/mobile/new.html.twig', [
            'mobile' => $mobile,
            'form' => $form,
        ]);
    }

    #[Route('/mobile/{id}/edit', name: 'app_admin_mobile_edit', methods: ['GET', 'POST'])]
    public function editMobile(Request $request, Mobile $mobile, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MobileType::class, $mobile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Móvil actualizado con éxito.');

            return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/communications/mobile/edit.html.twig', [
            'mobile' => $mobile,
            'form' => $form,
        ]);
    }

    #[Route('/mobile/{id}', name: 'app_admin_mobile_delete', methods: ['POST'])]
    public function deleteMobile(Request $request, Mobile $mobile, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mobile->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mobile);
            $entityManager->flush();

            $this->addFlash('success', 'Móvil eliminado con éxito.');
        }

        return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/phone-model/new', name: 'app_admin_phone_model_new', methods: ['GET', 'POST'])]
    public function newPhoneModel(Request $request, EntityManagerInterface $entityManager): Response
    {
        $phoneModel = new PhoneModel();
        $form = $this->createForm(PhoneModelType::class, $phoneModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($phoneModel);
            $entityManager->flush();

            $this->addFlash('success', 'Modelo de teléfono creado con éxito.');

            return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/communications/phone_model/new.html.twig', [
            'phone_model' => $phoneModel,
            'form' => $form,
        ]);
    }

    #[Route('/phone-model/{id}/edit', name: 'app_admin_phone_model_edit', methods: ['GET', 'POST'])]
    public function editPhoneModel(Request $request, PhoneModel $phoneModel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PhoneModelType::class, $phoneModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Modelo de teléfono actualizado con éxito.');

            return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/communications/phone_model/edit.html.twig', [
            'phone_model' => $phoneModel,
            'form' => $form,
        ]);
    }

    #[Route('/phone-model/{id}', name: 'app_admin_phone_model_delete', methods: ['POST'])]
    public function deletePhoneModel(Request $request, PhoneModel $phoneModel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$phoneModel->getId(), $request->request->get('_token'))) {
            $entityManager->remove($phoneModel);
            $entityManager->flush();

            $this->addFlash('success', 'Modelo de teléfono eliminado con éxito.');
        }

        return $this->redirectToRoute('app_admin_communications_index', [], Response::HTTP_SEE_OTHER);
    }
}
