<?php

namespace App\Service;

use App\Entity\Fichaje;
use App\Entity\Service;
use App\Entity\Volunteer;
use App\Entity\VolunteerService;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;

class CsvImportService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function import(string $filePath, int $year): array
    {
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        $stmt = (new Statement());
        $records = $stmt->process($csv);

        $successfulImports = 0;
        $errors = [];

        $volunteerRepo = $this->entityManager->getRepository(Volunteer::class);
        $serviceRepo = $this->entityManager->getRepository(Service::class);

        foreach ($records as $record) {
            $indicativo = $record['N° VOLUNTARIO'];
            $volunteer = $volunteerRepo->findOneBy(['indicativo' => $indicativo]);

            if (!$volunteer) {
                $errors[] = "Voluntario con indicativo '{$indicativo}' no encontrado.";
                continue;
            }

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
                            $errors[] = "Formato de fecha inválido para '{$dateStr}' en la fila del voluntario {$indicativo}.";
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

        $this->entityManager->flush();

        return [
            'successful_imports' => $successfulImports,
            'errors' => $errors,
        ];
    }
}
