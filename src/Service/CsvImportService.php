<?php

namespace App\Service;

use App\Entity\Fichaje;
use App\Entity\Service;
use App\Entity\Volunteer;
use App\Entity\VolunteerService;
use Doctrine\ORM\EntityManagerInterface;

class CsvImportService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function import(string $filePath, int $year): array
    {
        $successfulImports = 0;
        $errors = [];

        // Set locale to handle UTF-8 characters correctly
        setlocale(LC_ALL, 'en_US.UTF-8');

        if (($handle = fopen($filePath, 'r')) === false) {
            $errors[] = "No se pudo abrir el archivo CSV.";
            return ['successful_imports' => 0, 'errors' => $errors];
        }

        // Check for UTF-8 BOM
        $bom = fread($handle, 3);
        if ($bom !== "\xef\xbb\xbf") {
            rewind($handle);
        }

        // Leer la cabecera
        $header = fgetcsv($handle, 1000, ';');
        if ($header === false) {
            $errors[] = "No se pudo leer la cabecera del archivo CSV.";
            fclose($handle);
            return ['successful_imports' => 0, 'errors' => $errors];
        }

        if (!in_array('VOLUNTARIO', $header)) {
            $errors[] = "La cabecera del CSV debe contener una columna llamada 'VOLUNTARIO'.";
            fclose($handle);
            return ['successful_imports' => 0, 'errors' => $errors];
        }

        $volunteerRepo = $this->entityManager->getRepository(Volunteer::class);
        $serviceRepo = $this->entityManager->getRepository(Service::class);

        // Pre-cache all volunteers for faster lookup
        $allVolunteers = $volunteerRepo->findAll();
        $volunteerMap = [];
        foreach ($allVolunteers as $vol) {
            // Normalize the name for consistent matching
            $fullName = strtolower(trim(preg_replace('/\s+/', ' ', $vol->getName() . ' ' . $vol->getLastName())));
            $volunteerMap[$fullName] = $vol;
        }

        $rowNumber = 1;
        while (($data = fgetcsv($handle, 1000, ';')) !== false) {
            $rowNumber++;

            if (count($header) > count($data)) {
                $errors[] = "Fila {$rowNumber}: La fila tiene menos columnas que la cabecera, se omite.";
                continue;
            }

            if (count($header) < count($data)) {
                $data = array_slice($data, 0, count($header));
            }

            $record = array_combine($header, $data);

            $volunteerName = trim($record['VOLUNTARIO']);
            if (empty($volunteerName)) {
                continue;
            }

            $csvVolunteerName = strtolower(trim(preg_replace('/\s+/', ' ', $volunteerName)));

            if (!isset($volunteerMap[$csvVolunteerName])) {
                $errors[] = "Fila {$rowNumber}: Voluntario con nombre '{$volunteerName}' no encontrado.";
                continue;
            }
            $volunteer = $volunteerMap[$csvVolunteerName];

            for ($i = 1; isset($record['HORAS_' . $i]); $i++) {
                $hoursStr = $record['HORAS_' . $i];
                $dateStr = $record['FECHA_' . $i];
                $title = $record['COMENTARIO_' . $i];

                if (empty($hoursStr) && empty($dateStr) && empty($title)) {
                    continue; // Skip empty service entries at the end of a row
                }

                if (empty($hoursStr) || empty($dateStr) || empty($title)) {
                    $missing = [];
                    if (empty($hoursStr)) $missing[] = 'HORAS_' . $i;
                    if (empty($dateStr)) $missing[] = 'FECHA_' . $i;
                    if (empty($title)) $missing[] = 'COMENTARIO_' . $i;
                    $errors[] = "Fila {$rowNumber}: Faltan datos para la entrada de servicio {$i} ('" . implode(', ', $missing) . "'). Se omite esta entrada.";
                    continue;
                }

                // Handle date ranges like "01-02/01"
                if (strpos($dateStr, '-') !== false && strpos($dateStr, '/') !== false) {
                    list($startDay, $endDayMonth) = explode('-', $dateStr);
                    list($endDay, $month) = explode('/', $endDayMonth);
                    $dateStr = trim($startDay) . '/' . trim($month);
                }

                $startDate = \DateTime::createFromFormat('d/m/Y H:i:s', $dateStr . '/' . $year . ' 00:00:00');
                if ($startDate === false) {
                    $startDate = \DateTime::createFromFormat('d/m H:i:s', $dateStr . ' 00:00:00');
                    if ($startDate !== false) {
                        $startDate->setDate($year, $startDate->format('m'), $startDate->format('d'));
                    } else {
                        $errors[] = "Fila {$rowNumber}, Servicio {$i}: Formato de fecha inválido ('{$dateStr}') para '{$volunteerName}'.";
                        continue;
                    }
                }

                // Convert hours (e.g., "7,5") to interval
                $hours = (float)str_replace(',', '.', $hoursStr);
                $interval = new \DateInterval('PT' . (int)$hours . 'H' . (int)(($hours * 60) % 60) . 'M');
                $endDate = (clone $startDate)->add($interval);

                $service = $serviceRepo->findOneBy(['title' => $title, 'startDate' => $startDate]);

                if (!$service) {
                    $service = new Service();
                    $service->setTitle($title);
                    $service->setStartDate($startDate);
                    $service->setEndDate($endDate); // Assume service duration is the first entry's duration
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
                $fichaje->setClockIn(clone $startDate);
                $fichaje->setClockOut($endDate);
                $this->entityManager->persist($fichaje);

                $successfulImports++;
            }
        }

        fclose($handle);
        $this->entityManager->flush();

        return [
            'successful_imports' => $successfulImports,
            'errors' => $errors,
        ];
    }
}
