<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test-form', name: 'app_test_form')]
    public function testForm(EntityManagerInterface $entityManager): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service, [
            'action' => '#',
            'method' => 'POST',
        ]);

        return $this->render('test/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
