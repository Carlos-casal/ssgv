<?php

namespace App\Service;

use App\Entity\AssistanceConfirmation;
use App\Entity\Fichaje;
use App\Entity\Service;
use App\Entity\Volunteer;
use App\Entity\VolunteerService;
use App\Repository\AssistanceConfirmationRepository;
use App\Repository\ServiceRepository;
use App\Repository\VolunteerRepository;
use App\Repository\VolunteerServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CsvImportService
{
    private $entityManager;
    private $volunteerRepository;
    private $serviceRepository;
    private $volunteerServiceRepository;
    private $assistanceConfirmationRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        VolunteerRepository $volunteerRepository,
        ServiceRepository $serviceRepository,
        VolunteerServiceRepository $volunteerServiceRepository,
        AssistanceConfirmationRepository $assistanceConfirmationRepository
    ) {
        $this->entityManager = $entityManager;
        $this->volunteerRepository = $volunteerRepository;
        $this->serviceRepository = $serviceRepository;
        $this->volunteerServiceRepository = $volunteerServiceRepository;
        $this->assistanceConfirmationRepository = $assistanceConfirmationRepository;
    }

    public function import(UploadedFile $file): array
    {
        $results = ['success' => 0, 'errors' => []];
        $handle = fopen($file->getRealPath(), 'r');

        fgetcsv($handle, 0, ';'); // Skip header

        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            $volunteerId = $data[0];
            $volunteer = $this->volunteerRepository->findOneBy(['indicativo' => $volunteerId]);

            if (!$volunteer) {
                $results['errors'][] = "Volunteer with Nº {$volunteerId} not found.";
                continue;
            }

            for ($i = 3; $i < count($data); $i += 3) {
                $hours = $data[$i];
                $date = $data[$i + 1];
                $serviceName = $data[$i + 2];

                if (empty($hours) || empty($date) || empty($serviceName)) {
                    continue;
                }

                try {
                    $serviceDate = \DateTime::createFromFormat('d/m/y', $date) ?: \DateTime::createFromFormat('d/m/Y', $date);
                    if ($serviceDate === false) {
                        throw new \Exception("Invalid date format: {$date}");
                    }
                    $serviceDate->setTime(0, 0);

                    $service = $this->serviceRepository->findOneBy(['title' => $serviceName, 'startDate' => $serviceDate]);
                    if (!$service) {
                        $service = new Service();
                        $service->setTitle($serviceName);
                        $service->setStartDate($serviceDate);
                        $service->setEndDate(clone $serviceDate);
                        $this->entityManager->persist($service);
                        $this->entityManager->flush(); // Flush to get service ID
                    }

                    // Find or create VolunteerService
                    $volunteerService = $this->volunteerServiceRepository->findOneBy(['volunteer' => $volunteer, 'service' => $service]);
                    if (!$volunteerService) {
                        $volunteerService = new VolunteerService();
                        $volunteerService->setVolunteer($volunteer);
                        $volunteerService->setService($service);
                        $this->entityManager->persist($volunteerService);
                    }

                    // Ensure AssistanceConfirmation exists and is set to 'attending'
                    $assistance = $this->assistanceConfirmationRepository->findOneBy(['volunteer' => $volunteer, 'service' => $service]);
                    if (!$assistance) {
                        $assistance = new AssistanceConfirmation();
                        $assistance->setVolunteer($volunteer);
                        $assistance->setService($service);
                        $this->entityManager->persist($assistance);
                    }
                    $assistance->setStatus('attending');


                    // Create Fichaje
                    $startTime = clone $serviceDate;
                    $endTime = clone $startTime;
                    if (str_contains($hours, ':')) {
                        list($h, $m) = explode(':', $hours);
                        $endTime->add(new \DateInterval("PT{$h}H{$m}M"));
                    } else {
                        $minutes = (int)((float)$hours * 60);
                        $endTime->add(new \DateInterval("PT{$minutes}M"));
                    }

                    $fichaje = new Fichaje();
                    $fichaje->setVolunteerService($volunteerService); // Corrected method
                    $fichaje->setStartTime($startTime);
                    $fichaje->setEndTime($endTime);
                    $this->entityManager->persist($fichaje);

                    $results['success']++;

                } catch (\Exception $e) {
                    $results['errors'][] = "Error processing service '{$serviceName}' for volunteer {$volunteerId}: " . $e->getMessage();
                }
            }
        }

        fclose($handle);
        $this->entityManager->flush();

        return $results;
    }
}
