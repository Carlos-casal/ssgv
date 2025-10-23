<?php

namespace App\Service;

use App\Entity\AssistanceConfirmation;
use App\Entity\Fichaje;
use App\Entity\Service;
use App\Entity\Volunteer;
use App\Entity\VolunteerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CsvImportService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function import(UploadedFile $file, string $year): array
    {
        $report = ['success' => 0, 'errors' => [], 'skipped' => 0];

        $fileHandle = fopen($file->getRealPath(), 'r');
        if ($fileHandle === false) {
            $report['errors'][] = "No se pudo abrir el archivo subido.";
            return $report;
        }

        $header = fgetcsv($fileHandle, 0, ';');
        if ($header === false) {
            $report['errors'][] = "No se pudo leer la cabecera del archivo CSV.";
            fclose($fileHandle);
            return $report;
        }

        if (isset($header[0])) {
            $header[0] = preg_replace('/^\x{FEFF}/u', '', $header[0]);
        }

        $rowNumber = 1;
        while (($row = fgetcsv($fileHandle, 0, ';')) !== false) {
            $rowNumber++;

            if (count($header) > count($row)) {
                $row = array_pad($row, count($header), null);
            } elseif (count($header) < count($row)) {
                $row = array_slice($row, 0, count($header));
            }

            if (empty(array_filter($row))) {
                continue;
            }

            $rowData = array_combine($header, $row);
            $volunteerName = trim($rowData['VOLUNTARIO']);

            $volunteer = $this->entityManager->getRepository(Volunteer::class)->findOneBy(['name' => $volunteerName]);

            if (!$volunteer) {
                $report['errors'][] = "Fila {$rowNumber}: No se encontró al voluntario '{$volunteerName}'.";
                continue;
            }

            for ($i = 1; $i <= 60; $i++) {
                $hoursKey = "HORAS_{$i}";
                $dateKey = "FECHA_{$i}";
                $conceptKey = "COMENTARIO_{$i}";

                if (isset($rowData[$hoursKey]) && isset($rowData[$dateKey]) && isset($rowData[$conceptKey]) && !empty($rowData[$hoursKey]) && !empty($rowData[$dateKey]) && !empty($rowData[$conceptKey])) {
                    $serviceName = trim($rowData[$conceptKey]);
                    $dateString = trim($rowData[$dateKey]);
                    $date = $this->parseDate($dateString, $year);

                    if ($date === false) {
                        $report['errors'][] = "Fila {$rowNumber}: Formato de fecha inválido para '{$dateString}'.";
                        continue;
                    }

                    $serviceRepo = $this->entityManager->getRepository(Service::class);
                    $service = $serviceRepo->createQueryBuilder('s')
                        ->where('s.title = :title')
                        ->andWhere('s.startDate = :date')
                        ->setParameter('title', $serviceName)
                        ->setParameter('date', $date)
                        ->getQuery()
                        ->getOneOrNullResult();

                    if (!$service) {
                        $service = new Service();
                        $service->setTitle($serviceName);
                        $service->setStartDate($date);
                        $hours = (float)str_replace(',', '.', $rowData[$hoursKey]);
                        $endsAt = (clone $date)->modify('+' . round($hours * 3600) . ' seconds');
                        $service->setEndDate($endsAt);
                        $this->entityManager->persist($service);
                        $this->entityManager->flush();
                    }

                    $volunteerService = $this->entityManager->getRepository(VolunteerService::class)->findOneBy([
                        'volunteer' => $volunteer,
                        'service' => $service,
                    ]);

                    if (!$volunteerService) {
                        $volunteerService = new VolunteerService();
                        $volunteerService->setVolunteer($volunteer);
                        $volunteerService->setService($service);
                        $this->entityManager->persist($volunteerService);
                    }

                    $existingFichaje = $this->entityManager->getRepository(Fichaje::class)->findOneBy([
                        'volunteerService' => $volunteerService,
                        'startTime' => $date,
                    ]);

                    if ($existingFichaje) {
                        $report['skipped']++;
                        continue;
                    }

                    $assistance = $this->entityManager->getRepository(AssistanceConfirmation::class)->findOneBy([
                        'volunteer' => $volunteer,
                        'service' => $service,
                    ]);

                    if (!$assistance) {
                        $assistance = new AssistanceConfirmation();
                        $assistance->setVolunteer($volunteer);
                        $assistance->setService($service);
                        $this->entityManager->persist($assistance);
                    }
                    $assistance->setStatus(AssistanceConfirmation::STATUS_ATTENDING);

                    $fichaje = new Fichaje();
                    $fichaje->setVolunteerService($volunteerService);
                    $fichaje->setStartTime($date);
                    $hours = (float)str_replace(',', '.', $rowData[$hoursKey]);
                    $endsAt = (clone $date)->modify('+' . round($hours * 3600) . ' seconds');
                    $fichaje->setEndTime($endsAt);

                    $this->entityManager->persist($fichaje);
                    $report['success']++;
                }
            }
        }

        fclose($fileHandle);
        $this->entityManager->flush();

        return $report;
    }

    private function parseDate(string $dateString, string $year): \DateTime|false
    {
        // Handle DD/MM/YYYY format first
        $date = \DateTime::createFromFormat('d/m/Y', $dateString);
        if ($date && $date->format('d/m/Y') === $dateString) {
            return $date->setTime(0, 0);
        }

        // Handle date ranges like "01-31/12"
        if (preg_match('/^(\d{1,2})-(\d{1,2})\/(\d{1,2})$/', $dateString, $matches)) {
            $day = $matches[1];
            $month = $matches[3];
            $date = \DateTime::createFromFormat('d/m/Y', "$day/$month/$year");
            return $date ? $date->setTime(0, 0) : false;
        }

        // Handle dates like "04/12"
        if (preg_match('/^(\d{1,2})\/(\d{1,2})$/', $dateString, $matches)) {
            $day = $matches[1];
            $month = $matches[2];
            $date = \DateTime::createFromFormat('d/m/Y', "$day/$month/$year");
            return $date ? $date->setTime(0, 0) : false;
        }

        return false;
    }
}
