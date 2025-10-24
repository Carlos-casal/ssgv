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

        if (($handle = fopen($filePath, 'r')) === false) {
            $errors[] = "No se pudo abrir el archivo CSV.";
            return ['successful_imports' => 0, 'errors' => $errors];
        }

        // Leer la cabecera
        $header = fgetcsv($handle, 1000, ';');
        if ($header === false) {
            $errors[] = "No se pudo leer la cabecera del archivo CSV.";
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

        while (($data = fgetcsv($handle, 1000, ';')) !== false) {
            if (count($header) !== count($data)) {
                continue; // O manejar el error de una fila con un número incorrecto de columnas
            }
            $record = array_combine($header, $data);

            // Use the 'VOLUNTARIO' column and normalize the name
            $csvVolunteerName = strtolower(trim(preg_replace('/\s+/', ' ', $record['VOLUNTARIO'])));

            if (!isset($volunteerMap[$csvVolunteerName])) {
                $errors[] = "Voluntario con nombre '{$record['VOLUNTARIO']}' no encontrado.";
                continue;
            }
            $volunteer = $volunteerMap[$csvVolunteerName];

            for ($i = 1; isset($record['HORAS_' . $i]); $i++) {
                $hoursStr = $record['HORAS_' . $i];
                $dateStr = $record['FECHA_' . $i];
                $title = $record['COMENTARIO_' . $i];

                if (empty($hoursStr) || empty($dateStr) || empty($title)) {
                    continue;
                }

                try {
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
                            $errors[] = "Formato de fecha inválido para '{$dateStr}' en la fila del voluntario {$volunteer->getName()} {$volunteer->getLastName()}.";
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
                } catch (\Exception $e) {
                    $errors[] = "Error procesando registro para voluntario {$indicativo} y servicio '{$title}': " . $e->getMessage();
                }
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
