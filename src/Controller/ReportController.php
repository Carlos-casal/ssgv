<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VolunteerServiceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ReportController extends AbstractController
{
    #[Route('/reports', name: 'app_reports')]
    public function index(): Response
    {
        return $this->render('report/index.html.twig');
    }

    #[Route('/api/user-report', name: 'app_user_report_api')]
    public function userReport(VolunteerServiceRepository $volunteerServiceRepository, UserInterface $user, LoggerInterface $logger): JsonResponse
    {
        $logger->info('Fetching user report for user: ' . $user->getUserIdentifier());

        try {
            $volunteer = $user->getVolunteer();
            if (!$volunteer) {
                $logger->warning('User has no associated volunteer: ' . $user->getUserIdentifier());
                return new JsonResponse(['error' => 'User has no associated volunteer.'], 404);
            }

            $volunteerServices = $volunteerServiceRepository->findBy(['volunteer' => $volunteer]);
            $logger->info('Found ' . count($volunteerServices) . ' volunteer services for user: ' . $user->getUserIdentifier());

            $data = [];

            foreach ($volunteerServices as $volunteerService) {
                $data[] = [
                    'service' => $volunteerService->getService()->getTitle(),
                    'date' => $volunteerService->getService()->getDate()->format('d/m/Y'),
                    'startTime' => $volunteerService->getStartTime()->format('H:i'),
                    'endTime' => $volunteerService->getEndTime()->format('H:i'),
                    'duration' => $volunteerService->getDuration(),
                ];
            }

            return new JsonResponse($data);
        } catch (\Exception $e) {
            $logger->error('Error fetching user report: ' . $e->getMessage());
            return new JsonResponse(['error' => 'An error occurred while fetching the report.'], 500);
        }
    }
}
