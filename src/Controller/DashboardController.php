<?php

namespace App\Controller;

 
use App\Repository\VolunteerRepository;
use App\Repository\ServiceRepository;
use App\Repository\VehicleRepository;
use App\Repository\AssistanceConfirmationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(
        VolunteerRepository $volunteerRepository,
        ServiceRepository $serviceRepository,
        VehicleRepository $vehicleRepository,
        AssistanceConfirmationRepository $assistanceConfirmationRepository,
        Security $security
    ): Response {
        if ($this->getUser() && $this->isGranted('ROLE_ADMIN')) {
            $completedServicesThisYear = $serviceRepository->findCompletedServicesThisYear();
            $totalAnnualServiceMinutes = 0;
            foreach ($completedServicesThisYear as $service) {
                foreach ($service->getVolunteerServices() as $volunteerService) {
                    $totalAnnualServiceMinutes += $volunteerService->calculateTotalDuration();
                }
            }

            return $this->render('dashboard/admin_dashboard.html.twig', [
                'current_section' => 'inicio',
                'active_volunteers_count' => $volunteerRepository->countActiveVolunteers(),
                'new_volunteers_this_month' => $volunteerRepository->countNewVolunteersThisMonth(),
                'services_this_month' => $serviceRepository->countServicesThisMonth(),
                'available_vehicles_count' => $vehicleRepository->countAvailableVehicles(),
                'total_annual_service_hours' => round($totalAnnualServiceMinutes / 60),
                'completed_services_count' => count($completedServicesThisYear),
            ]);
        }

        if ($this->getUser() && $this->isGranted('ROLE_VOLUNTEER')) {
            $volunteer = $this->getUser()->getVolunteer();
            $services = $serviceRepository->findAll();
            $assistanceConfirmations = $assistanceConfirmationRepository->findBy(['volunteer' => $volunteer]);

            $assistanceByService = [];
            foreach ($assistanceConfirmations as $confirmation) {
                $assistanceByService[$confirmation->getService()->getId()] = $confirmation->hasAttended();
            }

            return $this->render('dashboard/volunteer_dashboard.html.twig', [
                'services' => $services,
                'assistanceByService' => $assistanceByService,
                'current_section' => 'inicio'
            ]);
        }

        return $this->redirectToRoute('app_login');
    }
}