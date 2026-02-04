<?php

namespace App\Controller;

use App\Entity\Volunteer;
use App\Entity\User;
use App\Entity\Fichaje;
use App\Entity\Material;
use App\Entity\VolunteerUniform;
use App\Entity\UniformMovement;
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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Http\Attribute\Security;

/**
 * Controller for managing volunteers, including listing, creation, editing, and reporting.
 */
class VolunteerController extends AbstractController
{
    /**
     * Displays a paginated list of volunteers with filtering and search capabilities.
     */
    #[Route('/voluntarios', name: 'app_volunteer_list')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function list(
        Request $request,
        VolunteerRepository $volunteerRepository,
        PaginatorInterface $paginator
    ): Response {
        $searchTerm = $request->query->get('search', '');
        $filterStatus = $request->query->get('status', 'all');
        $sort = $request->query->get('sort', 'indicativo');
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
     */
    #[Route('/alta-voluntario', name: 'app_volunteer_new', methods: ['GET', 'POST'])]
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
            $plainPassword = bin2hex(random_bytes(12));
            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

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
     */
    #[Route('/nueva_inscripcion', name: 'app_volunteer_registration', methods: ['GET', 'POST'])]
    public function registration(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, InvitationRepository $invitationRepository, KernelInterface $kernel): Response
    {
        $token = $request->query->get('token');
        $invitation = null;

        if ($kernel->getEnvironment() === 'dev' && $token === 'dummy-token-for-preview-only') {
            $volunteer = new Volunteer();
            $user = new User();
            $user->setEmail('test-email-for-preview@example.com');
            $volunteer->setUser($user);
        } else {
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
            $volunteer = $form->getData();
            $user = $volunteer->getUser();

            $plainPassword = $form->get('user')->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            }
            $user->setRoles(['ROLE_VOLUNTEER']);

            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                $newFilename = uniqid().'.'.$profilePictureFile->guessExtension();
                try {
                    $profilePictureFile->move($this->getParameter('kernel.project_dir').'/public/uploads/profile_pictures', $newFilename);
                    $volunteer->setProfilePicture($newFilename);
                } catch (FileException $e) {}
            }

            $volunteer->setStatus(Volunteer::STATUS_PENDING);
            if (!$volunteer->getJoinDate()) {
                $volunteer->setJoinDate(new \DateTime());
            }

            if ($invitation) {
                $invitation->setIsUsed(true);
                $entityManager->persist($invitation);
            }

            $entityManager->persist($user);
            $entityManager->persist($volunteer);
            $entityManager->flush();

            $this->addFlash('success', 'Solicitud de inscripción enviada correctamente.');
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
     */
    #[Route('/editar_voluntario-{id}', name: 'app_volunteer_edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function edit(Request $request, Volunteer $volunteer, EntityManagerInterface $entityManager, VolunteerRepository $volunteerRepository): Response
    {
        $availableIndicativos = $volunteerRepository->findAvailableIndicativos();
        if ($volunteer->getIndicativo() && !in_array($volunteer->getIndicativo(), $availableIndicativos)) {
            $availableIndicativos[] = $volunteer->getIndicativo();
            sort($availableIndicativos);
        }

        $form = $this->createForm(VolunteerType::class, $volunteer, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                $newFilename = uniqid().'.'.$profilePictureFile->guessExtension();
                $profilePictureFile->move($this->getParameter('kernel.project_dir').'/public/uploads/profile_pictures', $newFilename);
                $volunteer->setProfilePicture($newFilename);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Voluntario actualizado exitosamente.');
            return $this->redirectToRoute('app_volunteer_list');
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
     */
    #[Route('/exportar-csv', name: 'app_volunteer_export_csv')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function exportCsv(VolunteerRepository $volunteerRepository): Response
    {
        $volunteers = $volunteerRepository->findAll();
        $csvData = "Nombre,Apellidos,DNI,Email,Teléfono,Estado\n";

        foreach ($volunteers as $v) {
            $csvData .= sprintf("\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $v->getName(), $v->getLastName(), $v->getDni(),
                $v->getUser() ? $v->getUser()->getEmail() : '',
                $v->getPhone(), $v->getStatus());
        }

        $response = new Response($csvData);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="voluntarios.csv"');

        return $response;
    }

    /**
     * Renders a "coming soon" page for volunteer reports.
     */
    #[Route('/informes_voluntarios', name: 'app_volunteer_reports')]
    public function reports(): Response
    {
        return $this->render('common/coming_soon.html.twig', [
            'title' => 'Informes de Personal',
            'current_section' => 'personal-informes'
        ]);
    }

    /**
     * Generates and displays a report of a specific volunteer's logged hours.
     */
    #[Route('/{id}/informe-horas', name: 'app_volunteer_hours_report', methods: ['GET'])]
    public function hoursReport(Request $request, Volunteer $volunteer, FichajeRepository $fichajeRepository): Response
    {
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');

        $qb = $fichajeRepository->createQueryBuilder('f')
            ->innerJoin('f.volunteerService', 'vs')
            ->innerJoin('vs.service', 's')
            ->where('vs.volunteer = :volunteer')
            ->setParameter('volunteer', $volunteer)
            ->orderBy('f.startTime', 'DESC');

        if ($startDate) $qb->andWhere('f.startTime >= :start')->setParameter('start', new \DateTime($startDate));
        if ($endDate) $qb->andWhere('f.startTime <= :end')->setParameter('end', new \DateTime($endDate.' 23:59:59'));

        $fichajes = $qb->getQuery()->getResult();
        $totalHours = 0;
        $servicesData = [];

        foreach ($fichajes as $f) {
            $duration = $f->getEndTime() ? ($f->getEndTime()->getTimestamp() - $f->getStartTime()->getTimestamp()) / 3600 : 0;
            $totalHours += $duration;
            $servicesData[] = ['service' => $f->getVolunteerService()->getService(), 'date' => $f->getStartTime(), 'hours' => $duration];
        }

        return $this->render('volunteer/hours_report.html.twig', [
            'volunteer' => $volunteer,
            'servicesData' => $servicesData,
            'totalHours' => $totalHours,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    // --- MÉTODOS DE UNIFORMIDAD ---

    #[Route('/{id}/uniform/assign', name: 'app_volunteer_uniform_assign', methods: ['POST'])]
    public function assignUniform(Request $request, Volunteer $volunteer, EntityManagerInterface $entityManager): Response
    {
        $materialId = $request->request->get('material_id');
        $size = $request->request->get('size');
        $quantity = (int)$request->request->get('quantity', 1);
        $notes = $request->request->get('notes');

        $material = $entityManager->getRepository(Material::class)->find($materialId);
        if (!$material) {
            $this->addFlash('error', 'Material no encontrado.');
            return $this->redirectToRoute('app_volunteer_edit', ['id' => $volunteer->getId()]);
        }

        $uniform = new VolunteerUniform();
        $uniform->setVolunteer($volunteer);
        $uniform->setMaterial($material);
        $uniform->setSize($size);
        $uniform->setQuantity($quantity);
        $uniform->setStatus('active');
        $entityManager->persist($uniform);

        $movement = new UniformMovement();
        $movement->setVolunteer($volunteer);
        $movement->setMaterial($material);
        $movement->setMovementType(UniformMovement::TYPE_DELIVERY);
        $movement->setReason(UniformMovement::REASON_NEW_ASSIGNMENT);
        $movement->setSize($size);
        $movement->setQuantity($quantity);
        $movement->setNotes($notes);
        $movement->setCreatedBy($this->getUser());
        $entityManager->persist($movement);

        $entityManager->flush();
        $this->addFlash('success', 'Uniformidad asignada correctamente.');
        return $this->redirectToRoute('app_volunteer_edit', ['id' => $volunteer->getId()]);
    }

    #[Route('/{id}/uniform/return', name: 'app_volunteer_uniform_return', methods: ['POST'])]
    public function returnUniform(Request $request, Volunteer $volunteer, EntityManagerInterface $entityManager): Response
    {
        $uniformId = $request->request->get('uniform_id');
        $reason = $request->request->get('reason');
        $returnToStock = $request->request->get('return_to_stock') === 'yes';
        $newSize = $request->request->get('new_size');

        $uniform = $entityManager->getRepository(VolunteerUniform::class)->find($uniformId);
        if (!$uniform || $uniform->getVolunteer() !== $volunteer) {
            $this->addFlash('error', 'Uniformidad no encontrada.');
            return $this->redirectToRoute('app_volunteer_edit', ['id' => $volunteer->getId()]);
        }

        $material = $uniform->getMaterial();
        $movement = new UniformMovement();
        $movement->setVolunteer($volunteer);
        $movement->setMaterial($material);
        $movement->setMovementType($reason === 'size_change' ? UniformMovement::TYPE_EXCHANGE : UniformMovement::TYPE_RETURN);
        $movement->setReason($reason);
        $movement->setSize($uniform->getSize());
        $movement->setQuantity($uniform->getQuantity());
        $movement->setReturnToStock($returnToStock);
        $movement->setCreatedBy($this->getUser());
        $entityManager->persist($movement);

        if ($reason === 'size_change' && $newSize) {
            $uniform->setSize($newSize);
            $uniform->setAssignedAt(new \DateTime());
        } else {
            $uniform->setStatus('returned');
            $entityManager->remove($uniform);
        }

        if ($returnToStock && $material->getNature() === 'CONSUMIBLE') {
            $material->setStock($material->getStock() + $uniform->getQuantity());
        }

        $entityManager->flush();
        $this->addFlash('success', 'Operación completada correctamente.');
        return $this->redirectToRoute('app_volunteer_edit', ['id' => $volunteer->getId()]);
    }
}