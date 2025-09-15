<?php
namespace App\Controller;

use App\Entity\AssistanceConfirmation;
use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\AssistanceConfirmationRepository;
use App\Repository\ServiceRepository;
use App\Repository\VolunteerRepository;
use App\Entity\VolunteerService;
use App\Service\WhatsAppMessageGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class ServiceController extends AbstractController
{
    #[Route('/servicios', name: 'app_services_list', methods: ['GET'])]
    public function listServices(ServiceRepository $serviceRepository, \Symfony\Bundle\SecurityBundle\Security $security): Response
    {
        $user = $security->getUser();
        $services = $serviceRepository->findAll();

        if (empty($services)) {
            return $this->render('service/list_service.html.twig', [
                'services' => [],
                'attendeesByService' => [],
                'assistanceByService' => [],
            ]);
        }

        $attendeesByService = [];
        foreach ($services as $service) {
            $attendees = 0;
            foreach ($service->getAssistanceConfirmations() as $confirmation) {
                if ($confirmation->isHasAttended()) {
                    $attendees++;
                }
            }
            $attendeesByService[$service->getId()] = $attendees;
        }

        $assistanceByService = [];
        if ($this->isGranted('ROLE_VOLUNTEER')) {
            foreach ($services as $service) {
                $assistanceByService[$service->getId()] = false;
                foreach ($service->getAssistanceConfirmations() as $confirmation) {
                    if ($confirmation->getVolunteer()->getUser() === $user && $confirmation->isHasAttended()) {
                        $assistanceByService[$service->getId()] = true;
                    }
                }
            }
        }

        return $this->render('service/list_service.html.twig', [
            'services' => $services,
            'attendeesByService' => $attendeesByService,
            'assistanceByService' => $assistanceByService,
        ]);
    }

    #[Route('nuevo_servicio', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, WhatsAppMessageGenerator $messageGenerator): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($service);
            $entityManager->flush(); // Flush once to get the ID for URL generation

 
            $message = $messageGenerator->createMessage($service);
            if (!$service->getWhatsappMessage()) {
                $service->setWhatsappMessage($message);
                $entityManager->flush();
            }

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'success' => true,
                    'serviceId' => $service->getId(),
                    'whatsappMessage' => $message,
                ]);
            }

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'success' => true,
                    'serviceId' => $service->getId(),
                    'whatsappMessage' => $message,
                ]);
            }

            $this->addFlash('success', '¡El servicio ha sido creado con éxito!');
            $this->addFlash('info', 'Ahora puedes compartir el servicio por WhatsApp.');

            return $this->redirectToRoute('app_service_view', ['id' => $service->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid() && $request->isXmlHttpRequest()) {
            $errors = [];
            foreach ($form->getErrors(true, true) as $error) {
                $errors[] = $error->getMessage();
            }
            return new JsonResponse(['success' => false, 'errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        return $this->render('service/new_service.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/servicios/calendario/{year}/{month}', name: 'app_service_calendar', methods: ['GET'], defaults: ['year' => null, 'month' => null])]
    public function calendar(Request $request, ServiceRepository $serviceRepository, $year, $month): Response
    {
        $now = new \DateTime();
        $year = $year ?? $now->format('Y');
        $month = $month ?? $now->format('m');
        $services = $serviceRepository->findAll();
        return $this->render('service/calendar.html.twig', [
            'year' => $year,
            'month' => $month,
            'services' => $services,
        ]);
    }

    #[Route('/servicios/{id}', name: 'app_service_show', methods: ['GET'])]
    public function show(?Service $service): Response
    {
        if (!$service) {
            throw $this->createNotFoundException('El servicio solicitado no existe.');
        }
        $form = $this->createForm(ServiceType::class, $service);
        return $this->render('service/show_service.html.twig', [
            'service' => $service,
            'serviceForm' => $form->createView(),
        ]);
    }

    #[Route('/servicios/{id}/editar', name: 'app_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Servicio actualizado correctamente.');
            return $this->redirectToRoute('app_service_edit', ['id' => $service->getId()]);
        }

        return $this->render('service/edit_service.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/services/{id}/volunteers', name: 'app_service_get_volunteers', methods: ['GET'])]
    public function getVolunteers(Request $request, Service $service, VolunteerRepository $volunteerRepository, PaginatorInterface $paginator): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_COORDINATOR');

        $queryBuilder = $volunteerRepository->createQueryBuilder('v')
            ->leftJoin('v.assistanceConfirmations', 'ac', 'WITH', 'ac.service = :service')
            ->addSelect('ac')
            ->where('v.status = :status')
            ->setParameter('status', 'active')
            ->setParameter('service', $service);

        if ($request->query->has('search')) {
            $search = $request->query->get('search');
            if (!empty($search)) {
                $queryBuilder->andWhere('LOWER(v.name) LIKE LOWER(:search) OR LOWER(v.lastname) LIKE LOWER(:search) OR v.id LIKE :search')
                    ->setParameter('search', '%' . $search . '%');
            }
        }

        $query = $queryBuilder->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $data = [
            'items' => [],
            'pagination' => [
                'currentPage' => $pagination->getCurrentPageNumber(),
                'totalPages' => $pagination->getPageCount(),
                'totalCount' => $pagination->getTotalItemCount(),
            ]
        ];

        foreach ($pagination as $volunteer) {
            $data['items'][] = [
                'id' => $volunteer->getId(),
                'name' => $volunteer->getName() . ' ' . $volunteer->getLastname(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/services/{id}/update-attendance', name: 'app_service_update_attendance', methods: ['POST'])]
    public function updateAttendance(Request $request, Service $service, EntityManagerInterface $entityManager, VolunteerRepository $volunteerRepository, AssistanceConfirmationRepository $assistanceConfirmationRepository): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_COORDINATOR');

        $data = json_decode($request->getContent(), true);
        $volunteerIds = $data['volunteerIds'] ?? [];
        $status = $data['status'] ?? 'attends';

        if (empty($volunteerIds)) {
            return new JsonResponse(['success' => false, 'message' => 'No se han seleccionado voluntarios.'], Response::HTTP_BAD_REQUEST);
        }

        foreach ($volunteerIds as $volunteerId) {
            $volunteer = $volunteerRepository->find($volunteerId);
            if ($volunteer) {
                $confirmation = $assistanceConfirmationRepository->findOneBy(['service' => $service, 'volunteer' => $volunteer]);
                if (!$confirmation) {
                    $confirmation = new AssistanceConfirmation();
                    $confirmation->setService($service);
                    $confirmation->setVolunteer($volunteer);
                    $entityManager->persist($confirmation);
                }
                $attends = ($status === 'attends');
                $confirmation->setHasAttended($attends);
            }
        }

        $entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Asistencia actualizada correctamente.']);
    }

    #[Route('/servicio/{id}/asistir', name: 'app_service_attend', methods: ['GET'])]
    public function attend(?Service $service, EntityManagerInterface $entityManager, \Symfony\Bundle\SecurityBundle\Security $security, \App\Repository\AssistanceConfirmationRepository $assistanceConfirmationRepository): Response
    {
        if (!$service) {
            throw $this->createNotFoundException('El servicio solicitado no existe.');
        }

        if (!$this->isGranted('ROLE_VOLUNTEER')) {
            $this->addFlash('warning', 'Necesitas ser voluntario para inscribirte a un servicio.');
            return $this->redirectToRoute('app_access_denied');
        }

        $user = $security->getUser();
        $volunteer = $user->getVolunteer();
        $assistanceConfirmation = $assistanceConfirmationRepository->findOneBy(['volunteer' => $volunteer, 'service' => $service]);
        if (!$assistanceConfirmation) {
            $assistanceConfirmation = new \App\Entity\AssistanceConfirmation();
            $assistanceConfirmation->setVolunteer($volunteer);
            $assistanceConfirmation->setService($service);
            $entityManager->persist($assistanceConfirmation);
        }
        $assistanceConfirmation->setHasAttended(true);
        $entityManager->flush();
        $this->addFlash('success', 'Has confirmado tu asistencia.');
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/servicio/{id}/no-asistir', name: 'app_service_unattend', methods: ['GET'])]
    public function unattend(?Service $service, EntityManagerInterface $entityManager, \Symfony\Bundle\SecurityBundle\Security $security, \App\Repository\AssistanceConfirmationRepository $assistanceConfirmationRepository): Response
    {
        if (!$service) {
            throw $this->createNotFoundException('El servicio solicitado no existe.');
        }

        if (!$this->isGranted('ROLE_VOLUNTEER')) {
            $this->addFlash('warning', 'Necesitas ser voluntario para anular tu asistencia a un servicio.');
            return $this->redirectToRoute('app_access_denied');
        }

        $user = $security->getUser();
        $volunteer = $user->getVolunteer();
        $assistanceConfirmation = $assistanceConfirmationRepository->findOneBy(['volunteer' => $volunteer, 'service' => $service]);
        if (!$assistanceConfirmation) {
            $assistanceConfirmation = new \App\Entity\AssistanceConfirmation();
            $assistanceConfirmation->setVolunteer($volunteer);
            $assistanceConfirmation->setService($service);
            $entityManager->persist($assistanceConfirmation);
        }
        $assistanceConfirmation->setHasAttended(false);
        $entityManager->flush();
        $this->addFlash('success', 'Has confirmado tu no asistencia.');
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/servicios/{id}/asistencia', name: 'app_service_attendance', methods: ['GET'])]
    public function attendance(Service $service): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('service/attendance.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/mis-servicios', name: 'app_my_services', methods: ['GET'])]
    public function myServices(\App\Repository\VolunteerServiceRepository $volunteerServiceRepository, \Symfony\Bundle\SecurityBundle\Security $security): Response
    {
        $this->denyAccessUnlessGranted('ROLE_VOLUNTEER');
        $user = $security->getUser();
        $volunteerServices = $volunteerServiceRepository->findBy(['volunteer' => $user->getVolunteer()], ['startTime' => 'DESC']);
        $servicesByYear = [];
        foreach ($volunteerServices as $volunteerService) {
            if ($volunteerService->getDuration()) {
                $year = $volunteerService->getService()->getStartDate()->format('Y');
                if (!isset($servicesByYear[$year])) {
                    $servicesByYear[$year] = [];
                }
                $servicesByYear[$year][] = $volunteerService;
            }
        }
        $totalDurationCurrentYear = 0;
        $currentYear = date('Y');
        if (isset($servicesByYear[$currentYear])) {
            foreach ($servicesByYear[$currentYear] as $volunteerService) {
                $totalDurationCurrentYear += $volunteerService->getDuration();
            }
        }
        $lastService = $volunteerServiceRepository->findOneBy(['volunteer' => $user->getVolunteer()], ['startTime' => 'DESC']);
        return $this->render('service/my_services.html.twig', [
            'servicesByYear' => $servicesByYear,
            'totalDurationCurrentYear' => $totalDurationCurrentYear,
            'lastService' => $lastService,
        ]);
    }

    #[Route('/services/{year}/{month}/{day}', name: 'app_services_by_day', methods: ['GET'])]
    public function servicesByDay(ServiceRepository $serviceRepository, \App\Repository\AssistanceConfirmationRepository $assistanceConfirmationRepository, \Symfony\Bundle\SecurityBundle\Security $security, $year, $month, $day): Response
    {
        $date = new \DateTime("$year-$month-$day");
        $services = $serviceRepository->findByDate($date);
        $user = $security->getUser();
        $data = [];
        foreach ($services as $service) {
            $assistance = null;
            if ($user && $this->isGranted('ROLE_VOLUNTEER')) {
                $volunteer = $user->getVolunteer();
                $confirmation = $assistanceConfirmationRepository->findOneBy(['service' => $service, 'volunteer' => $volunteer]);
                if ($confirmation) {
                    $assistance = $confirmation->isHasAttended();
                }
            }
            $data[] = [
                'id' => $service->getId(),
                'title' => $service->getTitle(),
                'startDate' => $service->getStartDate() ? $service->getStartDate()->format('Y-m-d H:i:s') : null,
                'endDate' => $service->getEndDate() ? $service->getEndDate()->format('Y-m-d H:i:s') : null,
                'registrationLimitDate' => $service->getRegistrationLimitDate() ? $service->getRegistrationLimitDate()->format('Y-m-d H:i:s') : null,
                'assistance' => $assistance,
            ];
        }
        return $this->json($data);
    }

    #[Route('/service/{id}/view', name: 'app_service_view', methods: ['GET'])]
    public function view(Service $service, WhatsAppMessageGenerator $messageGenerator): Response
    {
        $message = $messageGenerator->createMessage($service);
        $whatsappLink = 'https://wa.me/?text=' . urlencode($message);
        return $this->render('service/view.html.twig', [
            'service' => $service,
            'whatsappLink' => $whatsappLink,
        ]);
    }
}