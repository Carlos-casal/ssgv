<?php

namespace App\Service;

use App\Entity\Fichaje;
use App\Entity\Service;
use App\Entity\Volunteer;
use App\Repository\ServiceRepository;
use App\Repository\VolunteerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CsvImportService
{
    private $entityManager;
    private $volunteerRepository;
    private $serviceRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        VolunteerRepository $volunteerRepository,
        ServiceRepository $serviceRepository
    ) {
        $this->entityManager = $entityManager;
        $this->volunteerRepository = $volunteerRepository;
        $this->serviceRepository = $serviceRepository;
    }

    public function import(UploadedFile $file): array
    {
        $results = ['success' => 0, 'errors' => []];
        $handle = fopen($file->getRealPath(), 'r');

        // Read the header row and ignore it
        fgetcsv($handle, 0, ';');

        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            // Your CSV: Nº;VOLUNTARIO;TOTAL;HORAS_x;FECHA_x;COMENTARIO_x;...
            // Column mapping: 0=>Nº, 1=>VOLUNTARIO, 2=>TOTAL (ignored)

            $volunteerId = $data[0];
            $volunteer = $this->volunteerRepository->findOneBy(['indicativo' => $volunteerId]);

            if (!$volunteer) {
                $results['errors'][] = "Volunteer with Nº {$volunteerId} not found.";
                continue;
            }

            // Loop through the dynamic service columns
            for ($i = 3; $i < count($data); $i += 3) {
                $hours = $data[$i];
                $date = $data[$i + 1];
                $serviceName = $data[$i + 2];

                if (empty($hours) || empty($date) || empty($serviceName)) {
                    continue; // Skip incomplete service entries
                }

                try {
                    // Find or create the service
                    $serviceDate = \DateTime::createFromFormat('d/m/y', $date);
                    if ($serviceDate === false) {
                         $serviceDate = \DateTime::createFromFormat('d/m/Y', $date);
                    }
                     if ($serviceDate === false) {
                        throw new \Exception("Invalid date format: {$date}");
                    }
                    $serviceDate->setTime(0, 0);


                    $service = $this->serviceRepository->findOneBy(['title' => $serviceName, 'startDate' => $serviceDate]);

                    if (!$service) {
                        $service = new Service();
                        $service->setTitle($serviceName);
                        $service->setStartDate($serviceDate);

                        // Set a default end date (e.g., same day)
                        $endDate = clone $serviceDate;
                        $service->setEndDate($endDate);

                        $this->entityManager->persist($service);
                    }

                    // Create Fichaje (clock-in/out record)
                    $startTime = clone $serviceDate; // Assuming work starts at the beginning of the service day for simplicity

                    // Calculate end time based on hours
                    // The hours can be in format 'HH:mm' or a decimal number
                    $endTime = clone $startTime;
                    if (str_contains($hours, ':')) {
                        list($h, $m) = explode(':', $hours);
                        $endTime->add(new \DateInterval("PT{$h}H{$m}M"));
                    } else {
                        $minutes = (float)$hours * 60;
                        $endTime->add(new \DateInterval("PT{$minutes}M"));
                    }

                    $fichaje = new Fichaje();
                    $fichaje->setVolunteer($volunteer);
                    $fichaje->setService($service);
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
