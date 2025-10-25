<?php

namespace App\Controller;

use App\Repository\AssistanceConfirmationRepository;
use App\Repository\ServiceRepository;
use App\Repository\VehicleRepository;
use App\Repository\VolunteerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class DashboardController extends AbstractController
{
    /**
     * Renders the dashboard by redirecting users based on their role.
     * This acts as the main entry point after login.
     */
    #[Route('/', name: 'app_dashboard')]
    public function index(Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        if ($this->getUser()) { // any logged in user
            return $this->redirectToRoute('app_volunteer_dashboard');
        }

        return $this->redirectToRoute('app_login');
    }

    /**
     * Renders the dashboard for administrators.
     */
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function adminDashboard(
        VolunteerRepository $volunteerRepository,
        ServiceRepository $serviceRepository,
        VehicleRepository $vehicleRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $completedServicesThisYear = $serviceRepository->findCompletedServicesThisYear();
        $totalAnnualServiceMinutes = 0;
        foreach ($completedServicesThisYear as $service) {
            $maxHoursInService = 0;
            foreach ($service->getVolunteerServices() as $volunteerService) {
                $duration = $volunteerService->calculateTotalDuration(); // duration in minutes
                if ($duration > $maxHoursInService) {
                    $maxHoursInService = $duration;
                }
            }
            $totalAnnualServiceMinutes += $maxHoursInService;
        }

        // Recent Activities
        $recentVolunteers = $volunteerRepository->findRecentActivityVolunteers();
        $recentServices = $serviceRepository->findCompletedServicesLast30Days();

        $recentActivities = array_merge($recentVolunteers, $recentServices);
        usort($recentActivities, function ($a, $b) {
            $dateA = null;
            if ($a instanceof \App\Entity\Volunteer) {
                $joinDate = $a->getJoinDate();
                $statusChangeDate = $a->getStatusChangeDate();
                $dateA = ($statusChangeDate && $statusChangeDate > $joinDate) ? $statusChangeDate : $joinDate;
            } else {
                $dateA = $a->getEndDate();
            }

            $dateB = null;
            if ($b instanceof \App\Entity\Volunteer) {
                $joinDate = $b->getJoinDate();
                $statusChangeDate = $b->getStatusChangeDate();
                $dateB = ($statusChangeDate && $statusChangeDate > $joinDate) ? $statusChangeDate : $joinDate;
            } else {
                $dateB = $b->getEndDate();
            }

            return $dateB <=> $dateA;
        });

        return $this->render('dashboard/admin_dashboard.html.twig', [
            'current_section' => 'inicio',
            'active_volunteers_count' => $volunteerRepository->countActiveVolunteers(),
            'new_volunteers_this_month' => $volunteerRepository->countNewVolunteersThisMonth(),
            'services_this_month' => $serviceRepository->countServicesThisMonth(),
            'available_vehicles_count' => $vehicleRepository->countAvailableVehicles(),
            'total_annual_service_hours' => round($totalAnnualServiceMinutes / 60),
            'completed_services_count' => count($completedServicesThisYear),
            'recent_activities' => $recentActivities,
            'upcoming_services' => $serviceRepository->findUpcomingServices(5),
        ]);
    }

    /**
     * Renders the dashboard for volunteers.
     */
    #[Route('/volunteer/dashboard', name: 'app_volunteer_dashboard')]
    public function volunteerDashboard(
        ServiceRepository $serviceRepository,
        AssistanceConfirmationRepository $assistanceConfirmationRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_VOLUNTEER');

        $volunteer = $this->getUser()->getVolunteer();
        if (!$volunteer) {
             throw $this->createNotFoundException('No volunteer profile found for this user.');
        }

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
}