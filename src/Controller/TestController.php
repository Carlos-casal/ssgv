<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test-age-modal', name: 'app_test_age_modal')]
    public function testAgeModal(): Response
    {
        return $this->render('test/age_modal.html.twig');
    }
}