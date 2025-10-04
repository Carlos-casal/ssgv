<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VolunteerServiceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Controller for generating and displaying reports.
 */
class ReportController extends AbstractController
{
    /**
     * Renders the main reports page.
     *
     * @return Response The response object.
     */
    #[Route('/reports', name: 'app_reports')]
    public function index(): Response
    {
        return $this->render('report/index.html.twig');
    }

    /**
     * Provides a JSON API endpoint for fetching a user's service report data.
     *
     * @param VolunteerServiceRepository $volunteerServiceRepository The repository for volunteer-service associations.
     * @param UserInterface $user The currently authenticated user.
     * @return JsonResponse A JSON response containing the user's service history.
     */
    #[Route('/api/user-report', name: 'app_user_report_api')]
    public function userReport(VolunteerServiceRepository $volunteerServiceRepository, UserInterface $user): JsonResponse
    {
        $volunteerServices = $volunteerServiceRepository->findBy(['volunteer' => $user]);
        $data = [];

        foreach ($volunteerServices as $volunteerService) {
            $firstFichaje = $volunteerService->getFichajes()->first();
            $lastFichaje = $volunteerService->getFichajes()->last();

            $data[] = [
                'service' => $volunteerService->getService()->getTitle(),
                'date' => $volunteerService->getService()->getDate()->format('d/m/Y'),
                'startTime' => $firstFichaje ? $firstFichaje->getStartTime()->format('H:i') : 'N/A',
                'endTime' => $lastFichaje && $lastFichaje->getEndTime() ? $lastFichaje->getEndTime()->format('H:i') : 'N/A',
                'duration' => $volunteerService->calculateTotalDuration(),
            ];
        }

        return new JsonResponse($data);
    }
}
