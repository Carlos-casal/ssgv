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
use Doctrine\ORM\EntityManagerInterface;
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

// #[Route('/personal')] // Comentario de línea para la ruta '/personal'
class VolunteerController extends AbstractController
{
    #[Route('/voluntarios', name: 'app_volunteer_list')]
    #[Security("is_granted('ROLE_ADMIN')")]
    // Inyecta PaginatorInterface para la paginación
    public function list(
        Request $request,
        VolunteerRepository $volunteerRepository,
        PaginatorInterface $paginator // Inyección del servicio de paginación
    ): Response {
        $searchTerm = $request->query->get('search', '');
        $filterStatus = $request->query->get('status', 'all');
        $page = $request->query->getInt('page', 1); // Obtiene el número de página de la URL, por defecto 1
        $limit = 10; // Define cuántos elementos quieres por página

        // Crea un QueryBuilder para construir la consulta de los voluntarios
        $queryBuilder = $volunteerRepository->createQueryBuilder('v')
                                            ->leftJoin('v.user', 'u') // Asume que Volunteer tiene una relación con User
                                            ->addSelect('u'); // Para poder buscar por email de usuario, si es necesario

        // Aplica filtros de búsqueda si hay un término
        if ($searchTerm) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('v.name', ':search'),
                    $queryBuilder->expr()->like('v.lastName', ':search'),
                    $queryBuilder->expr()->like('v.dni', ':search'),
                    $queryBuilder->expr()->like('u.email', ':search') // Búsqueda por email del usuario
                )
            )
            ->setParameter('search', '%' . $searchTerm . '%');
        }

        // Aplica filtros por estado si no es "all"
        if ($filterStatus !== 'all') {
            $queryBuilder->andWhere('v.status = :status')
                         ->setParameter('status', $filterStatus);
        }

        // Ordena los resultados para una paginación consistente
        $queryBuilder->orderBy('v.id', 'ASC');

        // Pagina la consulta usando el servicio KnpPaginator
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(), // Pasa el objeto Query resultante del QueryBuilder
            $page,                     // Número de página actual
            $limit                     // Elementos por página
        );

        // Estadísticas (estas siguen obteniendo todos los datos, podrías optimizarlas si el volumen es muy grande)
        $stats = [
            'total' => $volunteerRepository->count([]), // Usar count para eficiencia
            'Activo' => $volunteerRepository->count(['status' => Volunteer::STATUS_ACTIVE]),
            'Suspensión' => $volunteerRepository->count(['status' => Volunteer::STATUS_SUSPENDED]),
            'Baja' => $volunteerRepository->count(['status' => Volunteer::STATUS_INACTIVE]),
        ];

        return $this->render('volunteer/list_volunteer.html.twig', [
            'pagination' => $pagination, // ¡Pasamos el objeto de paginación a la vista!
            'stats' => $stats,
            'searchTerm' => $searchTerm,
            'filterStatus' => $filterStatus,
            'current_section' => 'personal-listado'
        ]);
    }

    #[Route('/nuevo_voluntario', name: 'app_volunteer_new', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $volunteer = new Volunteer();
        $user = new User(); // CORRECTO: Instanciar User aquí
        $volunteer->setUser($user); // Asociar el User al Volunteer

        $form = $this->createForm(VolunteerType::class, $volunteer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('user')->get('password')->getData();

            if ($plainPassword) { // Solo hashear si se proporcionó una contraseña
                $hashedPassword = $userPasswordHasher->hashPassword(
                    $user,
                    $plainPassword
                );
                $user->setPassword($hashedPassword);
            }
            $user->setRoles(['ROLE_VOLUNTEER']); // Usando ROLE_VOLUNTEER según tu código

            /** @var UploadedFile $profilePictureFile */
            $profilePictureFile = $form->get('profilePicture')->getData();

            // Manejar la subida del archivo de la foto
            if ($profilePictureFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Esto es para limpiar el nombre de archivo, haciéndolo seguro para URLs
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePictureFile->guessExtension();

                try {
                    $profilePictureFile->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads/profile_pictures', // Directorio de destino
                        $newFilename
                    );
                    // Si ya existe una foto (aunque en 'new' no debería haber, es buena práctica), bórrala.
                    // Esta lógica es más común en 'edit', pero no causa daño aquí.
                    if ($volunteer->getProfilePicture()) {
                        $filesystem = new Filesystem();
                        $oldFilePath = $this->getParameter('kernel.project_dir').'/public/uploads/profile_pictures/'.$volunteer->getProfilePicture();
                        if ($filesystem->exists($oldFilePath)) {
                            $filesystem->remove($oldFilePath);
                        }
                    }
                    // ¡IMPORTANTE! Asignar el nombre del archivo a la entidad Volunteer
                    $volunteer->setProfilePicture($newFilename);
                } catch (FileException $e) {
                    // Manejar el error de subida de archivo si ocurre
                    $this->addFlash('error', 'No se pudo subir la foto de perfil: ' . $e->getMessage());
                    // Puedes considerar no persistir el voluntario si la subida falla críticamente
                    // return $this->redirectToRoute('app_volunteer_new');
                }
            }

            // Establecer fecha de ingreso si no está definida
            if (!$volunteer->getJoinDate()) {
                $volunteer->setJoinDate(new \DateTime());
            }

            $entityManager->persist($user);
            $entityManager->persist($volunteer);
            $entityManager->flush();

            $this->addFlash('success', 'Voluntario creado exitosamente.');
            return $this->redirectToRoute('app_volunteer_list');
        }

        return $this->render('volunteer/new_volunterr.html.twig', [
            'volunteer' => $volunteer,
            'form' => $form->createView(),
            'current_section' => 'personal-nuevo'
        ]);
    }

    #[Route('/nueva_inscripcion', name: 'app_volunteer_registration', methods: ['GET', 'POST'])]
    public function registration(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $volunteer = new Volunteer();
        $user = new User(); // Creas la instancia de User
        $volunteer->setUser($user); // La asocias al Volunteer antes de crear el formulario

        $form = $this->createForm(VolunteerType::class, $volunteer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // *** AHORA, OBTENEMOS LA INSTANCIA DE USER QUE HA SIDO HIDRATADA POR EL FORMULARIO ***
            $hydratedVolunteer = $form->getData(); // Obtiene el objeto Volunteer con todos los datos rellenados por el formulario
            $hydratedUser = $hydratedVolunteer->getUser(); // Obtiene el objeto User que está dentro del Volunteer rellenado

            $lastVolunteer = $entityManager->getRepository(Volunteer::class)->findOneBy([], ['id' => 'DESC']);
            $expedientNumber = $lastVolunteer ? $lastVolunteer->getId() + 1 : 1;

            $plainPassword = $form->get('user')->get('password')->getData();

            if ($plainPassword) {
                // *** APLICAMOS EL HASHING A LA INSTANCIA DE USER CORRECTA ***
                $hashedPassword = $userPasswordHasher->hashPassword(
                    $hydratedUser, // ¡Usar $hydratedUser aquí!
                    $plainPassword
                );
                $hydratedUser->setPassword($hashedPassword); // ¡Setear la contraseña en $hydratedUser!
            }
            // *** ASIGNAMOS LOS ROLES A LA INSTANCIA DE USER CORRECTA ***
            $hydratedUser->setRoles(['ROLE_VOLUNTEER']); // ¡Usar $hydratedUser aquí!

            /** @var UploadedFile $profilePictureFile */
            $profilePictureFile = $form->get('profilePicture')->getData();

            // Manejar la subida del archivo de la foto
            if ($profilePictureFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $profilePictureFile->guessExtension();

                try {
                    $profilePictureFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/profile_pictures',
                        $newFilename
                    );
                    if ($hydratedVolunteer->getProfilePicture()) {
                        $filesystem = new Filesystem();
                        $oldFilePath = $this->getParameter('kernel.project_dir') . '/public/uploads/profile_pictures/' . $hydratedVolunteer->getProfilePicture();
                        if ($filesystem->exists($oldFilePath)) {
                            $filesystem->remove($oldFilePath);
                        }
                    }
                    $hydratedVolunteer->setProfilePicture($newFilename); // Asignar a $hydratedVolunteer
                } catch (FileException $e) {
                    $this->addFlash('error', 'No se pudo subir la foto de perfil: ' . $e->getMessage());
                }
            }

            $hydratedVolunteer->setStatus(Volunteer::STATUS_PENDING);
            if (!$hydratedVolunteer->getJoinDate()) {
                $hydratedVolunteer->setJoinDate(new \DateTime());
            }

            // *** PERSISTIR AMBAS ENTIDADES ***
            // Ahora, persistimos la instancia de User que ya fue rellenada y modificada
            $entityManager->persist($hydratedUser);
            // Y persistimos el Volunteer, que ya tiene la referencia al User correcto
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

    #[Route('/editar_voluntario-{id}', name: 'app_volunteer_edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function edit(Request $request, Volunteer $volunteer, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $volunteer->getUser();
        if (!$user) {
            // This should not happen in a normal flow if a volunteer must have a user.
            $this->addFlash('error', 'ERROR CRÍTICO: No hay un usuario asociado a este voluntario.');
            return $this->redirectToRoute('app_volunteer_list');
        }

        $form = $this->createForm(VolunteerType::class, $volunteer, [
            'is_edit' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle password update
            $plainPassword = $form->get('user')->get('password')->getData();
            if ($plainPassword) {
                $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            // Handle profile picture upload
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

                    // Remove old picture if it exists
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
            'current_section' => 'personal-editar',
        ]);
    }

    #[Route('/exportar-csv', name: 'app_volunteer_export_csv')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function exportCsv(VolunteerRepository $volunteerRepository): Response
    {
        $volunteers = $volunteerRepository->findAll();

        // Asegúrate de que el orden y la cantidad de columnas aquí coincidan con los datos que extraes
        $csvData = "Nombre,Apellidos,DNI,Email,Teléfono,Fecha Nacimiento,Tipo Calle,Dirección,Código Postal,Provincia,Contacto Emergencia,Teléfono Emergencia,Alergias,Profesión,Estado Empleo,Licencias Conducir,Licencias Navegación,Cualificaciones,Rol,Estado,Fecha Ingreso,Especialización\n";

        foreach ($volunteers as $volunteer) {
            $userEmail = $volunteer->getUser() ? $volunteer->getUser()->getEmail() : '';

            $csvData .= sprintf(
                "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
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
                $volunteer->getJoinDate() ? $volunteer->getJoinDate()->format('Y-m-d') : '',
                $volunteer->getSpecialization() ?? ''
            );
        }

        $response = new Response($csvData);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition',
            $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'voluntarios.csv')
        );

        return $response;
    }

    #[Route('/informes_voluntarios', name: 'app_volunteer_reports')]
    public function reports(): Response
    {
        return $this->render('common/coming_soon.html.twig', [
            'title' => 'Informes de Personal',
            'description' => 'Esta funcionalidad será implementada próximamente con todas las herramientas necesarias para una gestión eficiente.',
            'current_section' => 'personal-informes'
        ]);
    }

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