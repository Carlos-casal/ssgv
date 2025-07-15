<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VolunteerRegistrationController extends AbstractController
{
    #[Route('/formulario-inscripcion-voluntarios', name: 'app_volunteer_registration_form')]
    public function showRegistrationForm(): Response
    {
        // Aquí renderizarías la plantilla Twig para tu formulario de inscripción
        // Asegúrate de que el path del render sea correcto, por ejemplo:
        return $this->render('volunteer/registration_form.html.twig', [
            // Puedes pasar cualquier dato necesario para el formulario aquí
        ]);
    }
}