<?php

namespace App\Controller;

use App\Entity\Volunteer;
use App\Entity\User;
use App\Entity\Fichaje;
// Importa la entidad VolunteerService si ya la creaste.
// Si aún no la has creado, recuerda el paso 1 de mi respuesta anterior sobre cómo crearla
// use App\Entity\VolunteerService; // <--- Descomenta si ya tienes esta entidad
use App\Form\VolunteerType;
use App\Repository\VolunteerRepository;
use App\Repository\FichajeRepository;
use App\Repository\InvitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Knp\Component\Pager\PaginatorInterface; // ¡Importante!
use Symfony\Component\Security\Http\Attribute\Security;

/**
 * Controller for managing volunteers, including listing, creation, editing, and reporting.
 */
// #[Route('/personal')] // Comentario de línea para la ruta '/personal'
class VolunteerController extends AbstractController
{
    /**
     * Displays a paginated list of volunteers with filtering and search capabilities.
     *
     * @param Request $request The request object to handle search and filter parameters.
     * @param VolunteerRepository $volunteerRepository The repository for volunteers.
     * @param PaginatorInterface $paginator The KNP Paginator service.
     * @return Response The response object, rendering the volunteer list page.
     */
    #[Route('/voluntarios', name: 'app_volunteer_list')]
    #[Security("is_granted('ROLE_ADMIN')")]
    // Inyecta PaginatorInterface para la paginación
    public function list(
        Request $request,
        VolunteerRepository $volunteerRepository,
        PaginatorInterface $paginator
    ): Response {
        $searchTerm = $request->query->get('search', '');
        $filterStatus = $request->query->get('status', 'all');
        $sort = $request->query->get('sort', 'name');
        $direction = $request->query->get('direction', 'asc');

        $queryBuilder = $volunteerRepository->createQueryBuilder('v')
            ->leftJoin('v.user', 'u')
            ->addSelect('u');

        if ($searchTerm) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('v.name', ':search'),
                    $queryBuilder->expr()->like('v.lastName', ':search'),
                    $queryBuilder->expr()->like('v.indicativo', ':search')
                )
            )
            ->setParameter('search', '%' . $searchTerm . '%');
        }

        if ($filterStatus !== 'all') {
            $queryBuilder->andWhere('v.status = :status')
                         ->setParameter('status', $filterStatus);
        }

        $allowedSortFields = ['name', 'phone', 'indicativo'];
        if (in_array($sort, $allowedSortFields)) {
            $direction = strtolower($direction) === 'desc' ? 'DESC' : 'ASC';
            $queryBuilder->orderBy('v.' . $sort, $direction);
        } else {
            $queryBuilder->orderBy('v.name', 'ASC');
        }

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        $stats = [
            'total' => $volunteerRepository->count([]),
            'Activo' => $volunteerRepository->count(['status' => Volunteer::STATUS_ACTIVE]),
            'Suspensión' => $volunteerRepository->count(['status' => Volunteer::STATUS_SUSPENDED]),
            'Baja' => $volunteerRepository->count(['status' => Volunteer::STATUS_INACTIVE]),
        ];

        return $this->render('volunteer/list_volunteer.html.twig', [
            'pagination' => $pagination,
            'stats' => $stats,
            'searchTerm' => $searchTerm,
            'filterStatus' => $filterStatus,
            'current_section' => 'personal-listado'
        ]);
    }

    /**
     * Handles the creation of a new volunteer by an administrator.
     *
     * @param Request $request The request object.
     * @param EntityManagerInterface $entityManager The entity manager.
     * @param UserPasswordHasherInterface $userPasswordHasher The password hasher service.
     * @return Response The response object, rendering the new volunteer form or redirecting on success.
     */
    #[Route('/nuevo_voluntario', name: 'app_volunteer_new', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, VolunteerRepository $volunteerRepository): Response
    {
        $volunteer = new Volunteer();
        $user = new User();
        $volunteer->setUser($user);

        $availableIndicativos = $volunteerRepository->findAvailableIndicativos();
        $form = $this->createForm(VolunteerType::class, $volunteer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get email from the unmapped form field and set it on the User entity
            $email = $form->get('email')->getData();
            $user->setEmail($email);

            // Auto-generate a secure password
            $plainPassword = bin2hex(random_bytes(12)); // 24 characters
            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            // Set default roles
            $user->setRoles(['ROLE_VOLUNTEER']);
            $volunteer->setRole('Voluntario');

            /** @var UploadedFile $profilePictureFile */
            $profilePictureFile = $form->get('profilePicture')->getData();

            if ($profilePictureFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePictureFile->guessExtension();

                try {
                    $profilePictureFile->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads/profile_pictures',
                        $newFilename
                    );
                    $volunteer->setProfilePicture($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'No se pudo subir la foto de perfil: ' . $e->getMessage());
                }
            }

            if (!$volunteer->getJoinDate()) {
                $volunteer->setJoinDate(new \DateTime());
            }

            $entityManager->persist($volunteer);
            $entityManager->flush();

            // Flash the temporary password for the admin. In a real app, this would be emailed.
            $this->addFlash('success', 'Voluntario creado exitosamente. Contraseña temporal: ' . $plainPassword);
            return $this->redirectToRoute('app_volunteer_list');
        }

        return $this->render('volunteer/new_volunteer.html.twig', [
            'volunteer' => $volunteer,
            'form' => $form->createView(),
            'available_indicativos' => $availableIndicativos,
            'current_section' => 'personal-nuevo'
        ]);
    }

    /**
     * Handles the public registration form for new volunteers.
     * New registrations are set to a 'pending' status.
     *
     * @param Request $request The request object.
     * @param EntityManagerInterface $entityManager The entity manager.
     * @param UserPasswordHasherInterface $userPasswordHasher The password hasher service.
     * @return Response The response object, rendering the registration form or redirecting on success.
     */
    #[Route('/nueva_inscripcion', name: 'app_volunteer_registration', methods: ['GET', 'POST'])]
    public function registration(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, InvitationRepository $invitationRepository, KernelInterface $kernel): Response
    {
        $token = $request->query->get('token');
        $invitation = null;

        // Special case for dev environment preview link
        if ($kernel->getEnvironment() === 'dev' && $token === 'dummy-token-for-preview-only') {
            $volunteer = new Volunteer();
            $user = new User();
            $user->setEmail('test-email-for-preview@example.com');
            $volunteer->setUser($user);
        } else {
            // For production or any other token, validate against the database
            $invitation = $invitationRepository->findOneBy(['token' => $token]);

            if (!$invitation || $invitation->isUsed()) {
                return $this->render('error/unauthorized_invitation.html.twig');
            }

            $volunteer = new Volunteer();
            $user = new User();
            $user->setEmail($invitation->getEmail());
            $volunteer->setUser($user);
        }

        $form = $this->createForm(VolunteerType::class, $volunteer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hydratedVolunteer = $form->getData();
            $hydratedUser = $hydratedVolunteer->getUser();

            $lastVolunteer = $entityManager->getRepository(Volunteer::class)->findOneBy([], ['id' => 'DESC']);
            $expedientNumber = $lastVolunteer ? $lastVolunteer->getId() + 1 : 1;

            $plainPassword = $form->get('user')->get('password')->getData();
            if ($plainPassword) {
                $hashedPassword = $userPasswordHasher->hashPassword($hydratedUser, $plainPassword);
                $hydratedUser->setPassword($hashedPassword);
            }
            $hydratedUser->setRoles(['ROLE_VOLUNTEER']);

            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $profilePictureFile->guessExtension();
                try {
                    $profilePictureFile->move($this->getParameter('kernel.project_dir') . '/public/uploads/profile_pictures', $newFilename);
                    if ($hydratedVolunteer->getProfilePicture()) {
                        $filesystem = new Filesystem();
                        $oldFilePath = $this->getParameter('kernel.project_dir') . '/public/uploads/profile_pictures/' . $hydratedVolunteer->getProfilePicture();
                        if ($filesystem->exists($oldFilePath)) {
                            $filesystem->remove($oldFilePath);
                        }
                    }
                    $hydratedVolunteer->setProfilePicture($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'No se pudo subir la foto de perfil: ' . $e->getMessage());
                }
            }

            $hydratedVolunteer->setStatus(Volunteer::STATUS_PENDING);
            if (!$hydratedVolunteer->getJoinDate()) {
                $hydratedVolunteer->setJoinDate(new \DateTime());
            }

            // Mark the invitation as used, only if it's a real one from the database
            if ($invitation) {
                $invitation->setIsUsed(true);
                $entityManager->persist($invitation);
            }

            $entityManager->persist($hydratedUser);
            $entityManager->persist($hydratedVolunteer);
            $entityManager->flush();

            $this->addFlash('success', 'Solicitud de inscripción enviada correctamente. Número de expediente: ' . $expedientNumber);
            return $this->redirectToRoute('app_volunteer_registration');
        }

        return $this->render('volunteer/registration_form.html.twig', [
            'volunteer' => $volunteer,
            'form' => $form->createView(),
            'current_section' => 'personal-inscripcion'
        ]);
    }

    /**
     * Handles the editing of an existing volunteer's profile.
     *
     * @param Request $request The request object.
     * @param Volunteer $volunteer The volunteer entity to edit.
     * @param EntityManagerInterface $entityManager The entity manager.
     * @param UserPasswordHasherInterface $userPasswordHasher The password hasher service.
     * @return Response The response object, rendering the edit form or redirecting on success.
     */
    #[Route('/editar_voluntario-{id}', name: 'app_volunteer_edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function edit(Request $request, Volunteer $volunteer, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, VolunteerRepository $volunteerRepository): Response
    {
        $user = $volunteer->getUser();
        if (!$user) {
            $this->addFlash('error', 'ERROR CRÍTICO: No hay un usuario asociado a este voluntario.');
            return $this->redirectToRoute('app_volunteer_list');
        }

        $availableIndicativos = $volunteerRepository->findAvailableIndicativos();

        // Add the current volunteer's indicativo to the list if it exists, to allow re-selection
        if ($volunteer->getIndicativo() && !in_array($volunteer->getIndicativo(), $availableIndicativos)) {
            $availableIndicativos[] = $volunteer->getIndicativo();
            sort($availableIndicativos);
        }

        $form = $this->createForm(VolunteerType::class, $volunteer, [
            'is_edit' => true,
        ]);

        // Manually set the unmapped email field for display
        $form->get('email')->setData($user->getEmail());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle email update from the unmapped field
            $email = $form->get('email')->getData();
            $user->setEmail($email);

            // Password is not updated from this form, so password logic is removed.

            /** @var UploadedFile $profilePictureFile */
            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $profilePictureFile->guessExtension();

                try {
                    $profilePictureFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/profile_pictures',
                        $newFilename
                    );

                    if ($volunteer->getProfilePicture()) {
                        $filesystem = new Filesystem();
                        $oldFilePath = $this->getParameter('kernel.project_dir') . '/public/uploads/profile_pictures/' . $volunteer->getProfilePicture();
                        if ($filesystem->exists($oldFilePath)) {
                            $filesystem->remove($oldFilePath);
                        }
                    }

                    $volunteer->setProfilePicture($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'No se pudo subir la foto de perfil: ' . $e->getMessage());
                }
            }

            try {
                $entityManager->flush();
                $this->addFlash('success', 'Voluntario actualizado exitosamente.');
                return $this->redirectToRoute('app_volunteer_list', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error al actualizar el voluntario: ' . $e->getMessage());
            }
        }

        return $this->render('volunteer/edit_volunteer.html.twig', [
            'volunteer' => $volunteer,
            'form' => $form->createView(),
            'available_indicativos' => $availableIndicativos,
            'current_section' => 'personal-editar',
        ]);
    }

    /**
     * Exports all volunteer data to a CSV file.
     *
     * @param VolunteerRepository $volunteerRepository The repository for volunteers.
     * @return Response A response object containing the CSV file for download.
     */
    #[Route('/exportar-csv', name: 'app_volunteer_export_csv')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function exportCsv(VolunteerRepository $volunteerRepository): Response
    {
        $volunteers = $volunteerRepository->findAll();

        // Asegúrate de que el orden y la cantidad de columnas aquí coincidan con los datos que extraes
        $csvData = "Nombre,Apellidos,DNI,Email,Teléfono,Fecha Nacimiento,Tipo Calle,Dirección,Código Postal,Provincia,Contacto Emergencia,Teléfono Emergencia,Alergias,Profesión,Estado Empleo,Licencias Conducir,Licencias Navegación,Cualificaciones,Rol,Estado,Fecha Ingreso\n";

        foreach ($volunteers as $volunteer) {
            $userEmail = $volunteer->getUser() ? $volunteer->getUser()->getEmail() : '';

            $csvData .= sprintf(
                "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $volunteer->getName(),
                $volunteer->getLastName() ?? '',
                $volunteer->getDni() ?? '',
                $userEmail,
                $volunteer->getPhone(),
                $volunteer->getDateOfBirth() ? $volunteer->getDateOfBirth()->format('Y-m-d') : '',
                $volunteer->getStreetType() ?? '',
                $volunteer->getAddress() ?? '',
                $volunteer->getPostalCode() ?? '',
                $volunteer->getProvince() ?? '',
                $volunteer->getContactPerson1() ?? '',
                $volunteer->getContactPhone1() ?? '',
                $volunteer->getAllergies() ?? '',
                $volunteer->getProfession() ?? '',
                $volunteer->getEmploymentStatus() ?? '',
                implode(';', $volunteer->getDrivingLicenses() ?? []),
                implode(';', $volunteer->getNavigationLicenses() ?? []),
                implode(';', $volunteer->getSpecificQualifications() ?? []),
                $volunteer->getRole() ?? '',
                $volunteer->getStatus() ?? '',
                $volunteer->getJoinDate() ? $volunteer->getJoinDate()->format('Y-m-d') : ''
            );
        }

        $response = new Response($csvData);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition',
            $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'voluntarios.csv')
        );

        return $response;
    }

    /**
     * Renders a "coming soon" page for volunteer reports.
     *
     * @return Response The response object.
     */
    #[Route('/informes_voluntarios', name: 'app_volunteer_reports')]
    public function reports(): Response
    {
        return $this->render('common/coming_soon.html.twig', [
            'title' => 'Informes de Personal',
            'description' => 'Esta funcionalidad será implementada próximamente con todas las herramientas necesarias para una gestión eficiente.',
            'current_section' => 'personal-informes'
        ]);
    }

    /**
     * Generates and displays a report of a specific volunteer's logged hours, with filtering options.
     *
     * @param Request $request The request object for filtering.
     * @param Volunteer $volunteer The volunteer for whom the report is generated.
     * @param FichajeRepository $fichajeRepository The repository for clock-in/out records.
     * @return Response The response object, rendering the hours report page.
     */
    #[Route('/{id}/informe-horas', name: 'app_volunteer_hours_report', methods: ['GET'])]
    public function hoursReport(Request $request, Volunteer $volunteer, FichajeRepository $fichajeRepository): Response
    {
        $search = $request->query->get('search');
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');

        $qb = $fichajeRepository->createQueryBuilder('f')
            ->innerJoin('f.volunteerService', 'vs')
            ->innerJoin('vs.service', 's')
            ->where('vs.volunteer = :volunteer')
            ->setParameter('volunteer', $volunteer)
            ->orderBy('f.startTime', 'DESC');

        if ($search) {
            $qb->andWhere('s.title LIKE :search')
               ->setParameter('search', '%'.$search.'%');
        }

        if ($startDate) {
            $qb->andWhere('f.startTime >= :start_date')
               ->setParameter('start_date', new \DateTime($startDate));
        }

        if ($endDate) {
            $qb->andWhere('f.startTime <= :end_date')
               ->setParameter('end_date', new \DateTime($endDate . ' 23:59:59'));
        }

        $fichajes = $qb->getQuery()->getResult();

        $servicesData = [];
        $totalHours = 0;

        foreach ($fichajes as $fichaje) {
            $service = $fichaje->getVolunteerService()->getService();
            $serviceId = $service->getId();

            if (!isset($servicesData[$serviceId])) {
                $servicesData[$serviceId] = [
                    'service' => $service,
                    'totalHours' => 0,
                    'fichajes' => [],
                ];
            }

            $duration = 0;
            if ($fichaje->getEndTime()) {
                $duration = $fichaje->getEndTime()->getTimestamp() - $fichaje->getStartTime()->getTimestamp();
            }

            $hours = $duration / 3600;
            $servicesData[$serviceId]['totalHours'] += $hours;
            $servicesData[$serviceId]['fichajes'][] = [
                'date' => $fichaje->getStartTime(),
                'hours' => $hours,
            ];
            $totalHours += $hours;
        }

        return $this->render('volunteer/hours_report.html.twig', [
            'volunteer' => $volunteer,
            'servicesData' => $servicesData,
            'totalHours' => $totalHours,
            'search' => $search,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }
}