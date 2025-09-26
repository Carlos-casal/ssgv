<?php
namespace App\Controller;

use App\Entity\AssistanceConfirmation;
use App\Entity\Service;
use App\Form\ServiceType;
use App\Security\Voter\FichajeVoter;
use App\Repository\AssistanceConfirmationRepository;
use App\Repository\ServiceRepository;
use App\Repository\VolunteerServiceRepository;
use App\Repository\VolunteerRepository;
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
                if ($confirmation->getStatus() === AssistanceConfirmation::STATUS_ATTENDING) {
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
                    if ($confirmation->getVolunteer()->getUser() === $user && $confirmation->getStatus() === AssistanceConfirmation::STATUS_ATTENDING) {
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
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager, VolunteerServiceRepository $volunteerServiceRepository): Response
    {
        $this->denyAccessUnlessGranted(FichajeVoter::MANAGE_FICHANJE, $service);
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Servicio actualizado correctamente.');
            return $this->redirectToRoute('app_service_edit', ['id' => $service->getId()]);
        }

        $volunteerServices = $volunteerServiceRepository->findByServiceWithOrderedFichajes($service);

        $fichajesByVolunteer = [];
        foreach ($volunteerServices as $vs) {
            $fichajesByVolunteer[$vs->getVolunteer()->getId()] = $vs;
        }

        return $this->render('service/edit_service.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
            'fichajes' => $fichajesByVolunteer,
        ]);
    }

    #[Route('/services/{id}/volunteers', name: 'app_service_get_volunteers', methods: ['GET'])]
    public function getVolunteers(Request $request, Service $service, VolunteerRepository $volunteerRepository): JsonResponse
    {
        if (!$this->isGranted('ROLE_COORDINATOR') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('No tienes permiso para realizar esta acción.');
        }

        $queryBuilder = $volunteerRepository->createQueryBuilder('v');
        $queryBuilder->leftJoin(
                AssistanceConfirmation::class,
                'ac',
                'WITH',
                'ac.volunteer = v AND ac.service = :service'
            )
            ->where('v.status = :status')
            ->andWhere($queryBuilder->expr()->orX(
                'ac.id IS NULL',
                'ac.status != :attendingStatus'
            ))
            ->setParameter('status', \App\Entity\Volunteer::STATUS_ACTIVE)
            ->setParameter('service', $service)
            ->setParameter('attendingStatus', AssistanceConfirmation::STATUS_ATTENDING);

        if ($request->query->has('search')) {
            $search = $request->query->get('search');
            if (!empty($search)) {
                $queryBuilder->andWhere('LOWER(v.name) LIKE LOWER(:search) OR LOWER(v.lastName) LIKE LOWER(:search) OR v.id LIKE :search')
                    ->setParameter('search', '%' . $search . '%');
            }
        }

        // Manual pagination
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        
        // Clone the query builder to count total items without pagination
        $countQueryBuilder = clone $queryBuilder;
        $totalCount = $countQueryBuilder->select('count(v.id)')->getQuery()->getSingleScalarResult();
        
        $queryBuilder->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $volunteers = $queryBuilder->getQuery()->getResult();

        $data = [
            'items' => [],
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => ceil($totalCount / $limit),
                'totalCount' => $totalCount,
            ]
        ];

        foreach ($volunteers as $volunteer) {
            $data['items'][] = [
                'id' => $volunteer->getId(),
                'name' => $volunteer->getName(),
                'lastName' => $volunteer->getLastname(),
                'specialization' => $volunteer->getSpecialization(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/services/{id}/update-attendance', name: 'app_service_update_attendance', methods: ['POST'])]
    public function updateAttendance(Request $request, Service $service, EntityManagerInterface $entityManager, VolunteerRepository $volunteerRepository, AssistanceConfirmationRepository $assistanceConfirmationRepository): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_COORDINATOR')) {
            throw $this->createAccessDeniedException('No tienes permiso para realizar esta acción.');
        }

        $data = json_decode($request->getContent(), true);
        $volunteerIds = $data['volunteerIds'] ?? [];
        $status = $data['status'] ?? 'attends';

        if (empty($volunteerIds)) {
            return new JsonResponse(['success' => false, 'message' => 'No se han seleccionado voluntarios.'], Response::HTTP_BAD_REQUEST);
        }

        $maxAttendees = $service->getMaxAttendees();
        $currentAttendeesCount = $assistanceConfirmationRepository->count(['service' => $service, 'status' => AssistanceConfirmation::STATUS_ATTENDING]);
        $availableSlots = ($maxAttendees === null) ? count($volunteerIds) : $maxAttendees - $currentAttendeesCount;

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

                if ($status === 'attends') {
                    if ($availableSlots > 0) {
                        $confirmation->setStatus(AssistanceConfirmation::STATUS_ATTENDING);

                        // Also create the VolunteerService record if it doesn't exist
                        $volunteerService = $entityManager->getRepository(\App\Entity\VolunteerService::class)->findOneBy(['volunteer' => $volunteer, 'service' => $service]);
                        if (!$volunteerService) {
                            $volunteerService = new \App\Entity\VolunteerService();
                            $volunteerService->setVolunteer($volunteer);
                            $volunteerService->setService($service);
                            $entityManager->persist($volunteerService);
                        }

                        $availableSlots--;
                    } else {
                        $confirmation->setStatus(AssistanceConfirmation::STATUS_RESERVED);
                    }
                } else {
                    $confirmation->setStatus(AssistanceConfirmation::STATUS_NOT_ATTENDING);
                }
            }
        }

        $entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Asistencia actualizada correctamente.']);
    }

    #[Route('/assistance-confirmation/{id}/remove', name: 'app_assistance_confirmation_remove', methods: ['POST'])]
    public function removeAttendant(Request $request, AssistanceConfirmation $confirmation, EntityManagerInterface $entityManager, AssistanceConfirmationRepository $assistanceConfirmationRepository, VolunteerServiceRepository $volunteerServiceRepo): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_COORDINATOR')) {
            throw $this->createAccessDeniedException('No tienes permiso para realizar esta acción.');
        }

        if ($this->isCsrfTokenValid('delete'.$confirmation->getId(), $request->request->get('_token'))) {
            $service = $confirmation->getService();
            $volunteer = $confirmation->getVolunteer();
            $wasAttending = $confirmation->getStatus() === AssistanceConfirmation::STATUS_ATTENDING;

            // Find and remove the corresponding VolunteerService record
            $volunteerService = $volunteerServiceRepo->findOneBy(['volunteer' => $volunteer, 'service' => $service]);
            if ($volunteerService) {
                $entityManager->remove($volunteerService);
            }

            $entityManager->remove($confirmation);
            $entityManager->flush(); // Flush to remove the user and open a spot

            if ($wasAttending && $service->getMaxAttendees() !== null) {
                // Find the first person in the reserve list and promote them
                $reservedConfirmation = $assistanceConfirmationRepository->findOneBy(
                    ['service' => $service, 'status' => AssistanceConfirmation::STATUS_RESERVED],
                    ['createdAt' => 'ASC']
                );

                if ($reservedConfirmation) {
                    $reservedConfirmation->setStatus(AssistanceConfirmation::STATUS_ATTENDING);

                    // Create a VolunteerService record for the promoted volunteer
                    $promotedVolunteer = $reservedConfirmation->getVolunteer();
                    $promotedVs = $volunteerServiceRepo->findOneBy(['volunteer' => $promotedVolunteer, 'service' => $service]);
                    if (!$promotedVs) {
                        $promotedVs = new \App\Entity\VolunteerService();
                        $promotedVs->setVolunteer($promotedVolunteer);
                        $promotedVs->setService($service);
                        $entityManager->persist($promotedVs);
                    }

                    $entityManager->flush(); // Flush again to save the promoted user
                    $this->addFlash('success', 'Un voluntario de la lista de reserva ha sido movido a la lista de asistentes.');
                }
            }

            $this->addFlash('success', 'La confirmación de asistencia ha sido eliminada.');
        }

        return $this->redirectToRoute('app_service_edit', ['id' => $confirmation->getService()->getId(), '_fragment' => 'asistencias']);
    }

    #[Route('/assistance-confirmation/{id}/toggle-responsible', name: 'app_assistance_confirmation_toggle_responsible', methods: ['POST'])]
    public function toggleFichajeResponsible(Request $request, AssistanceConfirmation $confirmation, EntityManagerInterface $entityManager, AssistanceConfirmationRepository $assistanceConfirmationRepository): Response
    {
        $service = $confirmation->getService();
        $this->denyAccessUnlessGranted(FichajeVoter::MANAGE_FICHANJE, $service);

        if (!$this->isCsrfTokenValid('toggle-responsible'.$confirmation->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF inválido.');
            return $this->redirectToRoute('app_service_edit', ['id' => $service->getId()]);
        }

        $isCurrentlyResponsible = $confirmation->isFichajeResponsible();

        // If we are about to make this user responsible, we must first ensure no one else is.
        if (!$isCurrentlyResponsible) {
            $currentResponsible = $assistanceConfirmationRepository->findOneBy(['service' => $service, 'isFichajeResponsible' => true]);
            if ($currentResponsible && $currentResponsible !== $confirmation) {
                $currentResponsible->setFichajeResponsible(false);
            }
        }

        // Toggle the state for the selected user
        $confirmation->setFichajeResponsible(!$isCurrentlyResponsible);

        if ($isCurrentlyResponsible) {
            $this->addFlash('success', 'Se ha quitado la responsabilidad de fichaje al voluntario.');
        } else {
            $this->addFlash('success', 'Se ha asignado la responsabilidad de fichaje al voluntario.');
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_service_edit', ['id' => $service->getId(), '_fragment' => 'asistencias']);
    }

    #[Route('/servicio/{id}/asistir', name: 'app_service_attend', methods: ['GET'])]
    public function attend(?Service $service, EntityManagerInterface $entityManager, \Symfony\Bundle\SecurityBundle\Security $security, \App\Repository\AssistanceConfirmationRepository $assistanceConfirmationRepository, VolunteerServiceRepository $volunteerServiceRepo): Response
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

        $maxAttendees = $service->getMaxAttendees();
        if ($maxAttendees !== null) {
            $currentAttendeesCount = $assistanceConfirmationRepository->count(['service' => $service, 'status' => AssistanceConfirmation::STATUS_ATTENDING]);
            if ($currentAttendeesCount >= $maxAttendees) {
                $assistanceConfirmation->setStatus(AssistanceConfirmation::STATUS_RESERVED);
                $reservedCount = $assistanceConfirmationRepository->count(['service' => $service, 'status' => AssistanceConfirmation::STATUS_RESERVED]);
                $this->addFlash('info', 'El servicio está completo. Has sido añadido a la lista de reserva en la posición #' . ($reservedCount + 1) . '.');
            } else {
                $assistanceConfirmation->setStatus(AssistanceConfirmation::STATUS_ATTENDING);
                $this->addFlash('success', 'Has confirmado tu asistencia.');
            }
        } else {
            $assistanceConfirmation->setStatus(AssistanceConfirmation::STATUS_ATTENDING);
            $this->addFlash('success', 'Has confirmado tu asistencia.');
        }

        if ($assistanceConfirmation->getStatus() === AssistanceConfirmation::STATUS_ATTENDING) {
            $volunteerService = $volunteerServiceRepo->findOneBy(['volunteer' => $volunteer, 'service' => $service]);
            if (!$volunteerService) {
                $volunteerService = new \App\Entity\VolunteerService();
                $volunteerService->setVolunteer($volunteer);
                $volunteerService->setService($service);
                $entityManager->persist($volunteerService);
            }
        }

        $entityManager->flush();
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/servicio/{id}/no-asistir', name: 'app_service_unattend', methods: ['GET'])]
    public function unattend(?Service $service, EntityManagerInterface $entityManager, \Symfony\Bundle\SecurityBundle\Security $security, \App\Repository\AssistanceConfirmationRepository $assistanceConfirmationRepository, VolunteerServiceRepository $volunteerServiceRepo): Response
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

        // Remove the VolunteerService record if it exists
        $volunteerService = $volunteerServiceRepo->findOneBy(['volunteer' => $volunteer, 'service' => $service]);
        if ($volunteerService) {
            $entityManager->remove($volunteerService);
        }

        $assistanceConfirmation = $assistanceConfirmationRepository->findOneBy(['volunteer' => $volunteer, 'service' => $service]);
        if (!$assistanceConfirmation) {
            $assistanceConfirmation = new \App\Entity\AssistanceConfirmation();
            $assistanceConfirmation->setVolunteer($volunteer);
            $assistanceConfirmation->setService($service);
            $entityManager->persist($assistanceConfirmation);
        }
        $assistanceConfirmation->setStatus(AssistanceConfirmation::STATUS_NOT_ATTENDING);
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
        $volunteerServices = $volunteerServiceRepository->findForVolunteerOrderedByServiceDate($user->getVolunteer());

        $servicesByYear = [];
        $totalDurationByYear = [];

        foreach ($volunteerServices as $volunteerService) {
            $duration = $volunteerService->calculateTotalDuration();
            if ($duration > 0) {
                $year = $volunteerService->getService()->getStartDate()->format('Y');
                if (!isset($servicesByYear[$year])) {
                    $servicesByYear[$year] = [];
                    $totalDurationByYear[$year] = 0;
                }
                $servicesByYear[$year][] = $volunteerService;
                $totalDurationByYear[$year] += $duration;
            }
        }

        $currentYear = date('Y');
        $totalDurationCurrentYear = $totalDurationByYear[$currentYear] ?? 0;

        $lastService = $volunteerServices[0] ?? null;

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
                    $assistance = $confirmation->getStatus();
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

    #[Route('/servicios/responsable', name: 'app_responsible_services_list', methods: ['GET'])]
    public function responsibleServicesList(AssistanceConfirmationRepository $assistanceConfirmationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        if (!$user?->getVolunteer()) {
            // If the user is not a volunteer, they can't be responsible for anything.
            return $this->render('service/responsible_list.html.twig', ['services' => []]);
        }

        $responsibleConfirmations = $assistanceConfirmationRepository->findBy([
            'volunteer' => $user->getVolunteer(),
            'isFichajeResponsible' => true,
        ]);

        $services = array_map(fn($confirmation) => $confirmation->getService(), $responsibleConfirmations);

        return $this->render('service/responsible_list.html.twig', [
            'services' => $services,
        ]);
    }

    #[Route('/responsible/service/{id}', name: 'app_responsible_service_manage', methods: ['GET'])]
    public function manageResponsibleService(Service $service, VolunteerServiceRepository $volunteerServiceRepository): Response
    {
        // Use the voter to ensure the user is the designated responsible person, an admin, or a coordinator.
        $this->denyAccessUnlessGranted(FichajeVoter::MANAGE_FICHANJE, $service);

        $volunteerServices = $volunteerServiceRepository->findByServiceWithOrderedFichajes($service);

        $fichajesByVolunteer = [];
        foreach ($volunteerServices as $vs) {
            $fichajesByVolunteer[$vs->getVolunteer()->getId()] = $vs;
        }

        return $this->render('service/responsible_manage.html.twig', [
            'service' => $service,
            'fichajes' => $fichajesByVolunteer,
        ]);
    }
}