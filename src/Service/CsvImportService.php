<?php

namespace App\Service;

use App\Entity\Fichaje;
use App\Entity\Service;
use App\Entity\Volunteer;
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
        $csvData = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($csvData);

        foreach ($csvData as $rowNumber => $row) {
            $rowData = array_combine($header, $row);
            $volunteerName = $rowData['VOLUNTARIO'];

            $volunteer = $this->entityManager->getRepository(Volunteer::class)->findOneBy(['name' => $volunteerName]);

            if (!$volunteer) {
                $report['errors'][] = "Fila {$rowNumber}: No se encontró al voluntario '{$volunteerName}'.";
                continue;
            }

            for ($i = 1; $i <= 31; $i++) {
                $hoursKey = "HORAS_{$i}";
                $dateKey = "FECHA_{$i}";
                $conceptKey = "CONCEPTO_{$i}";

                if (!empty($rowData[$hoursKey]) && !empty($rowData[$dateKey]) && !empty($rowData[$conceptKey])) {
                    $serviceName = $rowData[$conceptKey];
                    $service = $this->entityManager->getRepository(Service::class)->findOneBy(['name' => $serviceName]);

                    if (!$service) {
                        $service = new Service();
                        $service->setName($serviceName);
                        // You may want to set other mandatory fields for Service entity here
                        $this->entityManager->persist($service);
                    }

                    $date = \DateTime::createFromFormat('d/m/Y', $rowData[$dateKey]);
                    if($date === false) {
                        $report['errors'][] = "Fila {$rowNumber}: Formato de fecha inválido para '{$rowData[$dateKey]}'. Use DD/MM/YYYY.";
                        continue;
                    }

                    $fichaje = new Fichaje();
                    $fichaje->setVolunteer($volunteer);
                    $fichaje->setService($service);
                    $fichaje->setStartsAt($date);
                    $endsAt = (clone $date)->modify('+' . (int)$rowData[$hoursKey] . ' hours');
                    $fichaje->setEndsAt($endsAt);

                    $this->entityManager->persist($fichaje);
                    $report['success']++;
                }
            }
        }

        $this->entityManager->flush();

        return $report;
    }
}
