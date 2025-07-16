<?php

namespace App\Controller;

use App\Entity\Volunteer;
use App\Entity\User;
// Importa la entidad VolunteerService si ya la creaste.
// Si aún no la has creado, recuerda el paso 1 de mi respuesta anterior sobre cómo crearla
// use App\Entity\VolunteerService; // <--- Descomenta si ya tienes esta entidad
use App\Form\VolunteerType;
use App\Repository\VolunteerRepository;
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

// #[Route('/personal')] // Comentario de línea para la ruta '/personal'
class VolunteerController extends AbstractController
{
    #[Route('/voluntarios', name: 'app_volunteer_list')]
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
            'Activo' => $volunteerRepository->count(['status' => 'Activo']),
            'Suspensión' => $volunteerRepository->count(['status' => 'Suspensión']),
            'Baja' => $volunteerRepository->count(['status' => 'Baja']),
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

            $hydratedVolunteer->setStatus('pending');
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
    public function edit(Request $request, Volunteer $volunteer, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $volunteer->getUser();
        $originalUserId = null;
        $originalUserEmail = 'N/A';
        $originalUserObjectHash = 'N/A';

        if ($user) {
            $originalUserId = $user->getId();
            $originalUserEmail = $user->getEmail();
            $originalUserObjectHash = spl_object_hash($user);
            $this->addFlash('debug_pre_form', 'Antes de crear formulario. User ID: ' . ($originalUserId ?: 'NULL') . ', Email: ' . $originalUserEmail . ', Hash: ' . $originalUserObjectHash);
        } else {
            // Esto no debería ocurrir en un flujo de edición si el voluntario siempre debe tener un usuario
            $this->addFlash('error', 'ERROR CRÍTICO: No hay usuario asociado al voluntario ANTES de crear el formulario.');
            return $this->redirectToRoute('app_volunteer_list'); 
        }

        $form = $this->createForm(VolunteerType::class, $volunteer, [
            'is_edit' => true,
        ]);

        $form->handleRequest($request);

        $userFromVolunteerAfterHandle = $volunteer->getUser();
        $userFromVolunteerAfterHandleId = $userFromVolunteerAfterHandle ? $userFromVolunteerAfterHandle->getId() : 'NULL';
        $userFromVolunteerAfterHandleEmail = $userFromVolunteerAfterHandle ? $userFromVolunteerAfterHandle->getEmail() : 'N/A';
        $userFromVolunteerAfterHandleObjectHash = $userFromVolunteerAfterHandle ? spl_object_hash($userFromVolunteerAfterHandle) : 'N/A';

        $this->addFlash('debug_post_handle', 'Después de handleRequest. User en Volunteer ID: ' . $userFromVolunteerAfterHandleId . ', Email: ' . $userFromVolunteerAfterHandleEmail . '. Hash Original: ' . $originalUserObjectHash . ', Hash Actual en Volunteer: ' . $userFromVolunteerAfterHandleObjectHash);

        if ($originalUserObjectHash !== $userFromVolunteerAfterHandleObjectHash && $userFromVolunteerAfterHandle !== null) {
            $this->addFlash('debug_post_handle_WARN', '¡ALERTA OBJETO USER CAMBIÓ! El hash del objeto User en Volunteer cambió después de handleRequest.');
        } else if ($userFromVolunteerAfterHandle !== null) {
            $this->addFlash('debug_post_handle_OK', 'El objeto User en Volunteer NO fue reemplazado (mismo hash) después de handleRequest.');
        } else {
             $this->addFlash('debug_post_handle_ERROR', 'ERROR: No hay usuario en Volunteer DESPUÉS de handleRequest.');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('debug_submit_valid', 'Formulario enviado y válido.');

            $userFromForm = $volunteer->getUser(); // Este es el usuario que tiene los datos del formulario (potencialmente nueva instancia)

            if (!$userFromForm) {
                 $this->addFlash('error', 'Error crítico en submit: No hay usuario en Volunteer tras ser válido el form.');
                return $this->render('volunteer/edit_volunteer.html.twig', ['volunteer' => $volunteer, 'form' => $form->createView(), 'current_section' => 'personal-editar']);
            }

            $finalUserEntity = null;
            // Estrategia: Si el objeto User fue reemplazado (diferente hash) y teníamos un ID original.
            if ($originalUserObjectHash !== spl_object_hash($userFromForm) && $originalUserId !== null) {
                $this->addFlash('debug_strategy', 'Estrategia: Objeto User fue reemplazado. Intentando actualizar entidad User original de BD (ID: ' . $originalUserId . ').');
                $userFromDb = $entityManager->getRepository(User::class)->find($originalUserId);
                if ($userFromDb) {
                    // Copiar datos del usuario del formulario ($userFromForm) al usuario de la BD ($userFromDb)
                    $userFromDb->setEmail($userFromForm->getEmail()); // Actualizar email
                    // La contraseña se maneja más abajo si se proveyó una nueva.
                    // Si UserType tuviera otros campos mapeados para User, se copiarían aquí.
                    
                    $volunteer->setUser($userFromDb); // RE-ASOCIAR la entidad User gestionada y correcta al Volunteer
                    $finalUserEntity = $userFromDb; // Esta es la entidad que Doctrine debe gestionar y actualizar
                    $this->addFlash('debug_strategy_OK', 'Entidad User original (ID: ' . $originalUserId . ') recuperada y re-asociada. Email ahora: ' . $userFromDb->getEmail());
                } else {
                    $this->addFlash('error', 'Error crítico: Usuario original (ID: ' . $originalUserId . ') no encontrado en BD. No se puede actualizar de forma segura.');
                    // Como fallback, podríamos intentar usar $userFromForm, pero es probable que falle con duplicado si el email no cambió.
                    // O mejor, mostrar error y no hacer flush.
                    $finalUserEntity = $userFromForm; // Esto es riesgoso si el email no es único para una NUEVA entidad.
                     $this->addFlash('debug_strategy_FAIL', 'Fallback: usando el objeto User del formulario (potencialmente problemático).');
                }
            } else {
                // El objeto User no fue reemplazado, o es un escenario donde no deberíamos cargar de BD (ej. nuevo voluntario, aunque aquí es 'edit').
                $finalUserEntity = $userFromForm;
                 $this->addFlash('debug_strategy', 'Estrategia: Objeto User no fue reemplazado o no había ID original válido. Usando User directamente del Volunteer.');
            }

            // Procesar contraseña con la entidad User final que hemos determinado
            $plainPassword = $form->get('user')->get('password')->getData();
            if ($plainPassword && $finalUserEntity) { 
                $hashedPassword = $userPasswordHasher->hashPassword($finalUserEntity, $plainPassword);
                $finalUserEntity->setPassword($hashedPassword);
                $this->addFlash('debug_password', 'Nueva contraseña hasheada y establecida para User ID: ' . ($finalUserEntity->getId() ?: 'NULL'));
            } else if ($plainPassword && !$finalUserEntity) {
                 $this->addFlash('debug_password_FAIL', 'Se proveyó contraseña pero finalUserEntity es NULL.');
            }

            /** @var UploadedFile $profilePictureFile */
            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePictureFile->guessExtension();
                try {
                    $profilePictureFile->move($this->getParameter('kernel.project_dir').'/public/uploads/profile_pictures', $newFilename);
                    if ($volunteer->getProfilePicture()) {
                        $filesystem = new Filesystem();
                        $oldFilePath = $this->getParameter('kernel.project_dir').'/public/uploads/profile_pictures/'.$volunteer->getProfilePicture();
                        if ($filesystem->exists($oldFilePath)) { $filesystem->remove($oldFilePath); }
                    }
                    $volunteer->setProfilePicture($newFilename);
                    $this->addFlash('debug_picture', 'Foto de perfil procesada.');
                } catch (FileException $e) {
                    $this->addFlash('error', 'No se pudo subir la foto de perfil: ' . $e->getMessage());
                }
            }
            
            // Depuración de UnitOfWork con finalUserEntity
            $unitOfWork = $entityManager->getUnitOfWork();
            if ($finalUserEntity) {
                $userState = $unitOfWork->getEntityState($finalUserEntity);
                $flashMessage = 'Estado del finalUserEntity (ID: ' . ($finalUserEntity->getId() ?: 'NULL') . ', Email: ' . $finalUserEntity->getEmail() . ') en UnitOfWork ANTES de flush: ';
                switch ($userState) {
                    case \Doctrine\ORM\UnitOfWork::STATE_NEW: $flashMessage .= 'NUEVO (NEW)'; break;
                    case \Doctrine\ORM\UnitOfWork::STATE_MANAGED: $flashMessage .= 'GESTIONADO (MANAGED)'; break;
                    case \Doctrine\ORM\UnitOfWork::STATE_DETACHED: $flashMessage .= 'DESVINCULADO (DETACHED)'; break;
                    case \Doctrine\ORM\UnitOfWork::STATE_REMOVED: $flashMessage .= 'ELIMINADO (REMOVED)'; break;
                    default: $flashMessage .= 'DESCONOCIDO (' . $userState . ')';
                }
                $this->addFlash('debug_uow_state', $flashMessage);
                if ($unitOfWork->isScheduledForInsert($finalUserEntity)) { $this->addFlash('debug_uow_insert', 'UOW: finalUserEntity está programado para INSERTAR.'); }
                if ($unitOfWork->isScheduledForUpdate($finalUserEntity)) { $this->addFlash('debug_uow_update', 'UOW: finalUserEntity está programado para ACTUALIZAR.'); }
                if ($unitOfWork->isScheduledForDelete($finalUserEntity)) { $this->addFlash('debug_uow_delete', 'UOW: finalUserEntity está programado para ELIMINAR.'); }
            } else {
                $this->addFlash('debug_uow_state_ERROR', 'ERROR: finalUserEntity es NULL antes de verificar UnitOfWork.');
            }

            $this->addFlash('debug_pre_flush', 'Intentando flush. Volunteer ID: ' . $volunteer->getId() . '. User asociado (finalUserEntity) ID: ' . ($finalUserEntity ? ($finalUserEntity->getId() ?: 'NULL') : 'NULL'));

            try {
                $entityManager->flush();
                $this->addFlash('success', 'Voluntario actualizado exitosamente.');
                return $this->redirectToRoute('app_volunteer_list', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error durante flush: ' . $e->getMessage());
                return $this->render('volunteer/edit_volunteer.html.twig', ['volunteer' => $volunteer, 'form' => $form->createView(), 'current_section' => 'personal-editar']);
            }

        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('debug_submit_invalid', 'Formulario enviado pero NO válido.');
            foreach ($form->getErrors(true, true) as $error) {
                $this->addFlash('debug_form_errors', $error->getOrigin()->getName() .': '. $error->getMessage());
            }
        }

        return $this->render('volunteer/edit_volunteer.html.twig', [
            'volunteer' => $volunteer,
            'form' => $form->createView(),
            'current_section' => 'personal-editar',
        ]);
    }

    #[Route('/exportar-csv', name: 'app_volunteer_export_csv')]
    public function exportCsv(VolunteerRepository $volunteerRepository): Response
    {
        $volunteers = $volunteerRepository->findAll();

        // Asegúrate de que el orden y la cantidad de columnas aquí coincidan con los datos que extraes
        $csvData = "Nombre,Apellidos,DNI,Email,Teléfono,Fecha Nacimiento,Tipo Calle,Dirección,Código Postal,Provincia,Contacto Emergencia,Teléfono Emergencia,Grupo Sanguíneo,Alergias,Condiciones Médicas,Profesión,Estado Empleo,Licencias Conducir,Licencias Navegación,Cualificaciones,Rol,Estado,Fecha Ingreso,Especialización\n";

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
                $volunteer->getEmergencyContactName() ?? '',
                $volunteer->getEmergencyContactPhone() ?? '',
                $volunteer->getBloodType() ?? '',
                $volunteer->getAllergies() ?? '',
                $volunteer->getMedicalConditions() ?? '',
                $volunteer->getProfession() ?? '',
                $volunteer->getEmploymentStatus() ?? '', // Corregido: Usar getEmploymentStatus() si existe
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
}