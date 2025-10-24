<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Crear usuario administrador de prueba',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Verificar si ya existe el usuario
        $existingUser = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => 'admin@voluntarios.org']);

        if ($existingUser) {
            $io->warning('El usuario admin@voluntarios.org ya existe.');
            return Command::SUCCESS;
        }

        $user = new User();
        $user->setEmail('admin@voluntarios.org');
        $user->setName('Administrador');
        $user->setRoles(['ROLE_ADMIN']);
        
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'admin123');
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Usuario administrador creado exitosamente!');
        $io->table(['Campo', 'Valor'], [
            ['Email', 'admin@voluntarios.org'],
            ['Password', 'admin123'],
            ['Rol', 'ROLE_ADMIN']
        ]);

        return Command::SUCCESS;
    }
}