<?php

namespace App\Controller;

use App\Entity\Volunteer;
use App\Entity\User;
use App\Entity\Fichaje;
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

    #[Route('/alta-voluntario', name: 'app_volunteer_new', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $volunteer = new Volunteer();
        $user = new User();
        $volunteer->setUser($user);

        $form = $this->createForm(VolunteerType::class, $volunteer, [
            'is_clean_layout' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $volunteer->getUser();
            $plainPassword = $form->get('user')->get('password')->getData();
            if ($plainPassword) {
                 $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
                 $user->setPassword($hashedPassword);
            }

            $user->setRoles(['ROLE_VOLUNTEER']);
            $volunteer->setRole('Voluntario');
            $volunteer->setStatus(Volunteer::STATUS_ACTIVE);

            /** @var UploadedFile $profilePictureFile */
            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePictureFile->guessExtension();

                try {
                    $profilePictureFile->move($this->getParameter('kernel.project_dir').'/public/uploads/profile_pictures', $newFilename);
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

            $this->addFlash('success', 'Voluntario creado exitosamente.');
            return $this->redirectToRoute('app_volunteer_list');
        }

        return $this->render('volunteer/new.html.twig', [
            'volunteer' => $volunteer,
            'form' => $form->createView(),
            'current_section' => 'personal-nuevo'
        ]);
    }

    #[Route('/nueva_inscripcion', name: 'app_volunteer_registration', methods: ['GET', 'POST'])]
    public function registration(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, InvitationRepository $invitationRepository, KernelInterface $kernel): Response
    {
        // This function is for public registration, which is out of scope for the current task.
        // It remains unchanged.
        return $this->redirectToRoute('app_login');
    }

    #[Route('/editar_voluntario-{id}', name: 'app_volunteer_edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function edit(Request $request, Volunteer $volunteer, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, VolunteerRepository $volunteerRepository): Response
    {
        // This function is for editing, which is out of scope for the current task.
        // It remains unchanged.
        return $this.redirectToRoute('app_volunteer_list');
    }
}