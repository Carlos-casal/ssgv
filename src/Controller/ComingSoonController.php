<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComingSoonController extends AbstractController
{
    #[Route('/servicios/listado', name: 'app_services_list')]
    public function servicesList(): Response
    {
        return $this->render('common/coming_soon.html.twig', [
            'title' => 'Listado de Servicios',
            'current_section' => 'servicios-listado'
        ]);
    }

    #[Route('/servicios/informes', name: 'app_services_reports')]
    public function servicesReports(): Response
    {
        return $this->render('common/coming_soon.html.twig', [
            'title' => 'Informes de Servicios',
            'current_section' => 'servicios-informes'
        ]);
    }

    #[Route('/servicios/cuadrantes', name: 'app_services_schedules')]
    public function servicesSchedules(): Response
    {
        return $this->render('common/coming_soon.html.twig', [
            'title' => 'Cuadrantes de Servicios',
            'current_section' => 'servicios-cuadrantes'
        ]);
    }

    #[Route('/comunicados', name: 'app_communications')]
    public function communications(): Response
    {
        return $this->render('common/coming_soon.html.twig', [
            'title' => 'Comunicados y Alertas',
            'current_section' => 'comunicados'
        ]);
    }

    #[Route('/vehiculos', name: 'app_vehicles')]
    public function vehicles(): Response
    {
        return $this->render('common/coming_soon.html.twig', [
            'title' => 'Gestión de Vehículos',
            'current_section' => 'vehiculos'
        ]);
    }

    #[Route('/gesdoc', name: 'app_gesdoc')]
    public function gesdoc(): Response
    {
        return $this->render('common/coming_soon.html.twig', [
            'title' => 'GESDOC - Gestión Documental',
            'current_section' => 'gesdoc'
        ]);
    }

    #[Route('/central', name: 'app_central')]
    public function central(): Response
    {
        return $this->render('common/coming_soon.html.twig', [
            'title' => 'Central de Comunicaciones',
            'current_section' => 'central'
        ]);
    }

    #[Route('/estadisticas', name: 'app_statistics')]
    public function statistics(): Response
    {
        return $this->render('common/coming_soon.html.twig', [
            'title' => 'Estadísticas y Reportes',
            'current_section' => 'estadisticas'
        ]);
    }
}