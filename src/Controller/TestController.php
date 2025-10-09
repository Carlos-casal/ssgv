<?php

namespace App\Controller;

use App\Form\TestFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/test-form', name: 'app_test_form')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(TestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle form submission if needed
            $this->addFlash('success', 'Form submitted successfully!');
            return $this->redirectToRoute('app_test_form');
        }

        return $this->render('test/test_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}