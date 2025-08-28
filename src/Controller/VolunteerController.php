<?php

namespace App\Controller;

use App\Entity\Volunteer;
use App\Entity\User;
use App\Form\ProfileType;
use App\Form\VolunteerType;
use App\Repository\VolunteerRepository;
use App\Service\FileUploader;
use App\Service\VolunteerManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\Security;
class VolunteerController extends AbstractController
{
    public function __construct(
        private readonly FileUploader $fileUploader,
        private readonly VolunteerManager $volunteerManager
    ) {
    }
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

        // Crea la consulta de los voluntarios usando el método del repositorio
        $queryBuilder = $volunteerRepository->createPaginatorQueryBuilder($searchTerm, $filterStatus);

        // Pagina la consulta usando el servicio KnpPaginator
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(), // Pasa el objeto Query resultante del QueryBuilder
            $page,                     // Número de página actual
            $limit                     // Elementos por página
        );

        // Estadísticas (estas siguen obteniendo todos los datos, podrías optimizarlas si el volumen es muy grande)
        $stats = $volunteerRepository->getStatusStats();

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
    public function new(Request $request): Response
    {
        $volunteer = new Volunteer();
        $user = new User(); // CORRECTO: Instanciar User aquí
        $volunteer->setUser($user); // Asociar el User al Volunteer

        $form = $this->createForm(VolunteerType::class, $volunteer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $profilePictureFile */
            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                try {
                    $newFilename = $this->fileUploader->upload($profilePictureFile);
                    $volunteer->setProfilePicture($newFilename);
                } catch (\RuntimeException $e) {
                    $this->addFlash('error', 'No se pudo subir la foto de perfil: ' . $e->getMessage());
                }
            }

            $plainPassword = $form->get('user')->get('password')->getData();
            $this->volunteerManager->processNewVolunteer($volunteer, $plainPassword);

            $this->addFlash('success', 'Voluntario creado exitosamente.');
            return $this->redirectToRoute('app_volunteer_list');
        }

        return $this->render('volunteer/new_volunteer.html.twig', [
            'volunteer' => $volunteer,
            'form' => $form->createView(),
            'current_section' => 'personal-nuevo'
        ]);
    }

    #[Route('/nueva_inscripcion', name: 'app_volunteer_registration', methods: ['GET', 'POST'])]
    public function registration(Request $request): Response
    {
        $volunteer = new Volunteer();
        $user = new User(); // Creas la instancia de User
        $volunteer->setUser($user); // La asocias al Volunteer antes de crear el formulario

        $form = $this->createForm(VolunteerType::class, $volunteer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $profilePictureFile */
            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                try {
                    $newFilename = $this->fileUploader->upload($profilePictureFile);
                    $volunteer->setProfilePicture($newFilename);
                } catch (\RuntimeException $e) {
                    $this->addFlash('error', 'No se pudo subir la foto de perfil: ' . $e->getMessage());
                }
            }

            $plainPassword = $form->get('user')->get('password')->getData();
            $this->volunteerManager->processRegistration($volunteer, $plainPassword);

            $this->addFlash('success', 'Solicitud de inscripción enviada correctamente.');
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
    public function edit(Request $request, Volunteer $volunteer): Response
    {
        if (!$volunteer->getUser()) {
            // This should not happen in a normal flow if a volunteer must have a user.
            $this->addFlash('error', 'ERROR CRÍTICO: No hay un usuario asociado a este voluntario.');
            return $this->redirectToRoute('app_volunteer_list');
        }

        $form = $this->createForm(VolunteerType::class, $volunteer, [
            'is_edit' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $profilePictureFile */
            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                try {
                    $oldFilename = $volunteer->getProfilePicture();
                    $newFilename = $this->fileUploader->upload($profilePictureFile, $oldFilename);
                    $volunteer->setProfilePicture($newFilename);
                } catch (\RuntimeException $e) {
                    $this->addFlash('error', 'No se pudo subir la foto de perfil: ' . $e->getMessage());
                }
            }

            $plainPassword = $form->get('user')->get('password')->getData();
            $this->volunteerManager->processUpdate($volunteer, $plainPassword);

            $this->addFlash('success', 'Voluntario actualizado exitosamente.');
            return $this->redirectToRoute('app_volunteer_list', [], Response::HTTP_SEE_OTHER);
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
                "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
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
    public function hoursReport(Volunteer $volunteer): Response
    {
        return $this->render('volunteer/hours_report.html.twig', [
            'volunteer' => $volunteer,
        ]);
    }

        #[Route('/perfil/editar', name: 'app_profile_edit', methods: ['GET', 'POST'])]
        #[Security("is_granted('ROLE_USER')")]
        public function editProfile(Request $request): Response
        {
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $volunteer = $user->getVolunteer();

            if (!$volunteer) {
                $this->addFlash('error', 'No se ha encontrado un perfil de voluntario asociado a tu cuenta.');
                return $this->redirectToRoute('app_dashboard');
            }

            $form = $this->createForm(ProfileType::class, $volunteer);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $plainPassword = $form->get('user')->get('password')->getData();
                $this->volunteerManager->processUpdate($volunteer, $plainPassword);

                $this->addFlash('success', 'Tu perfil ha sido actualizado correctamente.');

                return $this->redirectToRoute('app_profile_edit');
            }

            return $this->render('volunteer/edit_volunteer.html.twig', [
                'volunteer' => $volunteer,
                'form' => $form->createView(),
                'current_section' => 'perfil',
                'is_profile_page' => true,
            ]);
        }
}