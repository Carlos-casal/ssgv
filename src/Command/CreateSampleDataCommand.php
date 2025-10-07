<?php

namespace App\Command;

use App\Entity\Volunteer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-sample-data',
    description: 'Crear datos de ejemplo para voluntarios',
)]
class CreateSampleDataCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $sampleVolunteers = [
            [
                'name' => 'Ana Martínez López',
                'email' => 'ana.martinez@email.com',
                'phone' => '+34 600 123 456',
                'role' => 'Coordinador',
                'status' => 'active',
                'joinDate' => new \DateTime('2023-01-15')
            ],
            [
                'name' => 'Carlos García Ruiz',
                'email' => 'carlos.garcia@email.com',
                'phone' => '+34 600 234 567',
                'role' => 'Voluntario',
                'status' => 'active',
                'joinDate' => new \DateTime('2023-03-20')
            ],
            [
                'name' => 'María José Fernández',
                'email' => 'mj.fernandez@email.com',
                'phone' => '+34 600 345 678',
                'role' => 'Voluntario',
                'status' => 'inactive',
                'joinDate' => new \DateTime('2022-11-10')
            ],
            [
                'name' => 'Pedro Sánchez Díaz',
                'email' => 'pedro.sanchez@email.com',
                'phone' => '+34 600 456 789',
                'role' => 'Especialista',
                'status' => 'active',
                'joinDate' => new \DateTime('2023-02-05')
            ]
        ];

        foreach ($sampleVolunteers as $volunteerData) {
            $volunteer = new Volunteer();
            $volunteer->setName($volunteerData['name']);
            $volunteer->setEmail($volunteerData['email']);
            $volunteer->setPhone($volunteerData['phone']);
            $volunteer->setRole($volunteerData['role']);
            $volunteer->setStatus($volunteerData['status']);
            $volunteer->setJoinDate($volunteerData['joinDate']);

            $this->entityManager->persist($volunteer);
        }

        $this->entityManager->flush();

        $io->success('Datos de ejemplo creados exitosamente!');
        $io->note('Se han creado ' . count($sampleVolunteers) . ' voluntarios de ejemplo.');

        return Command::SUCCESS;
    }
}