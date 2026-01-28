<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test-form', name: 'app_test_form')]
    public function testForm(): Response
    {
        return $this->render('test/form.html.twig');
    }
}
