<?php

namespace App\Controller;

use App\Form\TestFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/pagina-de-prueba', name: 'app_test_page')]
    public function index(): Response
    {
        $form = $this->createForm(TestFormType::class);

        return $this->render('test/test_page.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
