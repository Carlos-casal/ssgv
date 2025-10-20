<?php

namespace App\Controller;

use App\Form\TestFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/pagina-de-prueba', name: 'app_test_page')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(TestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle successful submission
            // For a test page, we can just add a flash message
            $this->addFlash('success', '¡Formulario enviado con éxito!');
            return $this->redirectToRoute('app_test_page');
        }

        return $this->render('test/test_page.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
