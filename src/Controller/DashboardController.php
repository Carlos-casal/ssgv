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

/**
 * Controller for displaying the main dashboard.
 */
class DashboardController extends AbstractController
{
    /**
     * Renders the dashboard for the currently logged-in user.
     *
     * This method checks the user's role and renders the appropriate dashboard:
     * - Admin dashboard with statistics for administrators.
     * - Volunteer dashboard with service information for volunteers.
     * If the user is not logged in, it redirects to the login page.
     *
     * @param VolunteerRepository $volunteerRepository Repository for volunteer data.
     * @param ServiceRepository $serviceRepository Repository for service data.
     * @param VehicleRepository $vehicleRepository Repository for vehicle data.
     * @param AssistanceConfirmationRepository $assistanceConfirmationRepository Repository for assistance confirmation data.
     * @param Security $security The security component.
     * @return Response The response object, either a rendered template or a redirection.
     */
    #[Route('/', name: 'app_dashboard')]
    public function index(
        VolunteerRepository $volunteerRepository,
        ServiceRepository $serviceRepository,
        VehicleRepository $vehicleRepository,
        AssistanceConfirmationRepository $assistanceConfirmationRepository,
        \App\Repository\MaterialRepository $materialRepository,
        Security $security
    ): Response {
        if ($this->getUser() && $this->isGranted('ROLE_ADMIN')) {
            $startOfYear = new \DateTime('first day of january this year 00:00:00');
            $endOfYear = new \DateTime('last day of december this year 23:59:59');

            $totalAnnualServiceMinutes = $serviceRepository->calculateTotalServiceMinutes($startOfYear, $endOfYear);

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
                'low_stock_materials_count' => $materialRepository->countLowStockMaterials(),
                'total_annual_service_hours' => round($totalAnnualServiceMinutes / 60),
                'completed_services_count' => $serviceRepository->countCompletedServices(),
                'recent_activities' => $recentActivities,
                'upcoming_services' => $serviceRepository->findUpcomingServices(5),
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
