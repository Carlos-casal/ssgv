<?php

namespace App\Controller;

 
use App\Repository\VolunteerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(VolunteerRepository $volunteerRepository): Response
    {
        $volunteers = $volunteerRepository->findAll();
        $activeVolunteers = $volunteerRepository->findByStatus('active');
        
        $stats = [
            'total_volunteers' => count($volunteers),
            'active_volunteers' => count($activeVolunteers),
            'coordinators' => count(array_filter($volunteers, fn($v) => $v->getRole() === 'Coordinador')),
            'specialists' => count(array_filter($volunteers, fn($v) => $v->getRole() === 'Especialista')),
        ];

        $recentActivities = [
            [
                'action' => 'Nuevo voluntario registrado',
                'user' => 'María García',
                'time' => 'Hace 2 horas',
                'type' => 'success'
            ],
            [
                'action' => 'Servicio completado',
                'user' => 'Equipo Alpha',
                'time' => 'Hace 4 horas',
                'type' => 'info'
            ],
            [
                'action' => 'Mantenimiento vehículo',
                'user' => 'Taller Municipal',
                'time' => 'Hace 1 día',
                'type' => 'warning'
            ],
            [
                'action' => 'Informe mensual generado',
                'user' => 'Sistema',
                'time' => 'Hace 2 días',
                'type' => 'info'
            ]
        ];

        $upcomingEvents = [
            [
                'title' => 'Formación en Primeros Auxilios',
                'date' => '15 Mar 2024',
                'time' => '10:00',
                'participants' => 25
            ],
            [
                'title' => 'Simulacro de Emergencia',
                'date' => '18 Mar 2024',
                'time' => '09:00',
                'participants' => 40
            ],
            [
                'title' => 'Reunión Coordinadores',
                'date' => '22 Mar 2024',
                'time' => '16:00',
                'participants' => 8
            ]
        ];

        return $this->render('dashboard/index.html.twig', [
            'stats' => $stats,
            'recent_activities' => $recentActivities,
            'upcoming_events' => $upcomingEvents,
            'current_section' => 'inicio'
        ]);
    }
}