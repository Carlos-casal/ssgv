<?php

namespace App\Controller\Admin;

use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Setting;
use App\Form\SettingType;

#[Route('/admin/settings')]
class SettingController extends AbstractController
{
    #[Route('/', name: 'admin_settings')]
    public function index(Request $request, SettingRepository $settingRepository, EntityManagerInterface $entityManager): Response
    {
        // Get all settings from the database
        $settings = $settingRepository->findAll();
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting->getSettingKey()] = $setting->getSettingValue();
        }

        $form = $this->createForm(SettingType::class, $settingsArray);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            foreach ($data as $key => $value) {
                $setting = $settingRepository->findOneBy(['settingKey' => $key]);
                if (!$setting) {
                    $setting = new Setting();
                    $setting->setSettingKey($key);
                }
                $setting->setSettingValue($value);
                $entityManager->persist($setting);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Settings updated successfully.');

            return $this->redirectToRoute('admin_settings');
        }

        return $this->render('admin/setting/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}