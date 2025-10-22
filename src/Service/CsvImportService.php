<?php

namespace App\Service;

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

    public function import(UploadedFile $file): array
    {
        $report = ['success' => 0, 'errors' => []];

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
            $volunteerName = $rowData['VOLUNTARIO'];

            $volunteer = $this->entityManager->getRepository(Volunteer::class)->findOneBy(['name' => $volunteerName]);

            if (!$volunteer) {
                $report['errors'][] = "Fila {$rowNumber}: No se encontró al voluntario '{$volunteerName}'.";
                continue;
            }

            for ($i = 1; $i <= 60; $i++) {
                $hoursKey = "HORAS_{$i}";
                $dateKey = "FECHA_{$i}";
                $conceptKey = "COMENTARIO_{$i}";

                if (!empty($rowData[$hoursKey]) && !empty($rowData[$dateKey]) && !empty($rowData[$conceptKey])) {
                    $serviceName = $rowData[$conceptKey];
                    $dateString = $rowData[$dateKey];
                    $date = $this->parseDate($dateString);

                    if ($date === false) {
                        $report['errors'][] = "Fila {$rowNumber}: Formato de fecha inválido para '{$dateString}'.";
                        continue;
                    }

                    $service = $this->entityManager->getRepository(Service::class)->findOneBy(['title' => $serviceName]);

                    if (!$service) {
                        $service = new Service();
                        $service->setTitle($serviceName);
                        $service->setStartDate($date);
                        $hours = (float)str_replace(',', '.', $rowData[$hoursKey]);
                        $endsAt = (clone $date)->modify('+' . round($hours * 3600) . ' seconds');
                        $service->setEndDate($endsAt);
                        $this->entityManager->persist($service);
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

    private function parseDate(string $dateString): \DateTime|false
    {
        // Handle date ranges like "01-31/12"
        if (preg_match('/^(\d{1,2})-(\d{1,2})\/(\d{1,2})$/', $dateString, $matches)) {
            $day = $matches[1];
            $month = $matches[3];
            $year = date('Y');
            $date = \DateTime::createFromFormat('d/m/Y', "$day/$month/$year");
            return $date ? $date->setTime(0, 0) : false;
        }

        // Handle dates like "04/12"
        if (preg_match('/^(\d{1,2})\/(\d{1,2})$/', $dateString, $matches)) {
            $day = $matches[1];
            $month = $matches[2];
            $year = date('Y');
            $date = \DateTime::createFromFormat('d/m/Y', "$day/$month/$year");
            return $date ? $date->setTime(0, 0) : false;
        }

        // Handle dates like "DD/MM/YYYY"
        $date = \DateTime::createFromFormat('d/m/Y', $dateString);
        return $date ? $date->setTime(0, 0) : false;
    }
}
