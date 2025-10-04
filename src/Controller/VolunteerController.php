<?php

namespace App\Controller;

use App\Entity\ActivityLog;
use App\Entity\Volunteer;
use App\Entity\User;
use App\Entity\Fichaje;
use App\Form\VolunteerType;
use App\Repository\VolunteerRepository;
use App\Repository\FichajeRepository;
use Doctrine\ORM\EntityManagerInterface;
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

class VolunteerController extends AbstractController
{
    #[Route('/voluntarios', name: 'app_volunteer_list')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function list(
        Request $request,
        VolunteerRepository $volunteerRepository,
        PaginatorInterface $paginator
    ): Response {
        $searchTerm = $request->query->get('search', '');
        $filterStatus = $request->query->get('status', 'all');
        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $queryBuilder = $volunteerRepository->createQueryBuilder('v')
                                            ->leftJoin('v.user', 'u')
                                            ->addSelect('u');

        if ($searchTerm) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('v.name', ':search'),
                    $queryBuilder->expr()->like('v.lastName', ':search'),
                    $queryBuilder->expr()->like('v.dni', ':search'),
                    $queryBuilder->expr()->like('u.email', ':search')
                )
            )
            ->setParameter('search', '%' . $searchTerm . '%');
        }

        if ($filterStatus !== 'all') {
            $queryBuilder->andWhere('v.status = :status')
                         ->setParameter('status', $filterStatus);
        }

        $queryBuilder->orderBy('v.id', 'ASC');

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $page,
            $limit
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

    #[Route('/nuevo_voluntario', name: 'app_volunteer_new', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $volunteer = new Volunteer();
        $user = new User();
        $volunteer->setUser($user);

        $form = $this->createForm(VolunteerType::class, $volunteer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('user')->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            }
            $user->setRoles(['ROLE_VOLUNTEER']);

            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                $newFilename = $this->uploadProfilePicture($profilePictureFile, $volunteer);
                $volunteer->setProfilePicture($newFilename);
            }

            if (!$volunteer->getJoinDate()) {
                $volunteer->setJoinDate(new \DateTime());
            }

            $entityManager->persist($user);
            $entityManager->persist($volunteer);

            $activityLog = new ActivityLog();
            $activityLog->setType('VOLUNTEER');
            $activityLog->setDescription(sprintf('Nuevo voluntario "%s" registrado por un administrador.', $volunteer->getName()));
            $entityManager->persist($activityLog);

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
        $user = new User();
        $volunteer->setUser($user);

        $form = $this->createForm(VolunteerType::class, $volunteer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hydratedVolunteer = $form->getData();
            $hydratedUser = $hydratedVolunteer->getUser();

            $plainPassword = $form->get('user')->get('password')->getData();
            if ($plainPassword) {
                $hydratedUser->setPassword($userPasswordHasher->hashPassword($hydratedUser, $plainPassword));
            }
            $hydratedUser->setRoles(['ROLE_VOLUNTEER']);

            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                $newFilename = $this->uploadProfilePicture($profilePictureFile, $hydratedVolunteer);
                $hydratedVolunteer->setProfilePicture($newFilename);
            }

            $hydratedVolunteer->setStatus(Volunteer::STATUS_PENDING);
            if (!$hydratedVolunteer->getJoinDate()) {
                $hydratedVolunteer->setJoinDate(new \DateTime());
            }

            $entityManager->persist($hydratedUser);
            $entityManager->persist($hydratedVolunteer);

            $activityLog = new ActivityLog();
            $activityLog->setType('VOLUNTEER');
            $activityLog->setDescription(sprintf('Nueva solicitud de inscripción del voluntario "%s".', $hydratedVolunteer->getName()));
            $entityManager->persist($activityLog);

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

    #[Route('/invitation/{token}', name: 'app_volunteer_registration_from_invitation', methods: ['GET', 'POST'])]
    public function registrationFromInvitation(
        string $token,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher,
        \App\Repository\InvitationRepository $invitationRepository
    ): Response {
        $invitation = $invitationRepository->findOneBy(['token' => $token]);

        if (!$invitation || $invitation->isUsed() || $invitation->isExpired()) {
            $this->addFlash('error', 'El enlace de invitación no es válido o ha caducado.');
            return $this->redirectToRoute('app_login');
        }

        $volunteer = new Volunteer();
        $user = new User();
        $user->setEmail($invitation->getEmail());
        $volunteer->setUser($user);

        $form = $this->createForm(VolunteerType::class, $volunteer, ['is_invitation' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('user')->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            }
            $user->setRoles(['ROLE_VOLUNTEER']);
            $volunteer->setStatus(Volunteer::STATUS_ACTIVE);
            $volunteer->setJoinDate(new \DateTime());

            $invitation->setIsUsed(true);

            $entityManager->persist($user);
            $entityManager->persist($volunteer);
            $entityManager->flush();

            $this->addFlash('success', '¡Bienvenido! Tu registro se ha completado con éxito.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('volunteer/registration_form.html.twig', [
            'form' => $form->createView(),
            'email' => $invitation->getEmail(),
        ]);
    }

    #[Route('/editar_voluntario-{id}', name: 'app_volunteer_edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function edit(Request $request, Volunteer $volunteer, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $volunteer->getUser();
        if (!$user) {
            $this->addFlash('error', 'ERROR CRÍTICO: No hay un usuario asociado a este voluntario.');
            return $this->redirectToRoute('app_volunteer_list');
        }

        $originalStatus = $volunteer->getStatus();
        $form = $this->createForm(VolunteerType::class, $volunteer, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('user')->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            }

            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                $newFilename = $this->uploadProfilePicture($profilePictureFile, $volunteer);
                $volunteer->setProfilePicture($newFilename);
            }

            $newStatus = $volunteer->getStatus();
            if ($originalStatus !== $newStatus) {
                $activityLog = new ActivityLog();
                $activityLog->setType('VOLUNTEER');
                $statusMap = [
                    Volunteer::STATUS_ACTIVE => 'activado',
                    Volunteer::STATUS_SUSPENDED => 'suspendido',
                    Volunteer::STATUS_INACTIVE => 'dado de baja',
                    Volunteer::STATUS_PENDING => 'movido a pendiente',
                ];
                $statusText = $statusMap[$newStatus] ?? $newStatus;
                $activityLog->setDescription(sprintf('El voluntario "%s" ha sido %s.', $volunteer->getName(), $statusText));
                $entityManager->persist($activityLog);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Voluntario actualizado exitosamente.');
            return $this->redirectToRoute('app_volunteer_list');
        }

        return $this->render('volunteer/edit_volunteer.html.twig', [
            'volunteer' => $volunteer,
            'form' => $form->createView(),
            'current_section' => 'personal-editar',
        ]);
    }

    private function uploadProfilePicture(UploadedFile $file, Volunteer $volunteer): ?string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
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
            return $newFilename;
        } catch (FileException $e) {
            $this->addFlash('error', 'No se pudo subir la foto de perfil: ' . $e->getMessage());
            return null;
        }
    }
}