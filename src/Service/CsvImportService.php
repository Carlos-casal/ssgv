<?php

namespace App\Service;

use App\Entity\Fichaje;
use App\Entity\Service;
use App\Entity\Volunteer;
use App\Entity\VolunteerService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class CsvImportService
{
    private const BATCH_SIZE = 50;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * Imports volunteer hours from a CSV file.
     *
     * @param string $filePath The path to the CSV file.
     * @param int    $year     The year of the services.
     *
     * @return array An array containing the number of successful imports and any errors.
     */
    public function importVolunteerHours(string $filePath, int $year): array
    {
        $handle = fopen($filePath, 'r');
        if (false === $handle) {
            throw new \RuntimeException('No se pudo abrir el archivo.');
        }

        // Read header row
        $headers = fgetcsv($handle, 0, ';');
        if (false === $headers) {
            throw new \RuntimeException('No se pudo leer la cabecera del CSV.');
        }

        // BOM detection and removal
        if (isset($headers[0]) && str_starts_with($headers[0], "\xEF\xBB\xBF")) {
            $headers[0] = substr($headers[0], 3);
        }

        $results = ['successful' => 0, 'errors' => []];
        $rowCount = 1;
        $batchCount = 0;

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $rowCount++;
            // Pad the row with null values if it has fewer columns than the header
            $row = array_pad($row, \count($headers), null);
            $data = array_combine($headers, $row);

            $volunteerName = trim($data['VOLUNTARIO']);
            $volunteer = $this->em->getRepository(Volunteer::class)->findOneBy(['name' => $volunteerName]);

            if (!$volunteer) {
                $results['errors'][] = sprintf('Fila %d: No se encontró al voluntario "%s".', $rowCount, $volunteerName);
                continue;
            }

            for ($i = 1; isset($data['HORAS_'.$i]); ++$i) {
                $hoursStr = trim($data['HORAS_'.$i]);
                $dateStr = trim($data['FECHA_'.$i]);
                $serviceTitle = trim($data['COMENTARIO_'.$i]);

                if (empty($hoursStr) || empty($dateStr) || empty($serviceTitle)) {
                    continue;
                }

                $hours = (float) str_replace(',', '.', $hoursStr);
                if ($hours <= 0) {
                    continue;
                }

                try {
                    // Handle date ranges (e.g., "01-31/12")
                    if (str_contains($dateStr, '/')) {
                        [$start, $end] = explode('/', $dateStr, 2);
                        // Take the start of the range as the service date
                        $dateStr = $start;
                    }

                    $date = \DateTime::createFromFormat('d/m', $dateStr);
                    if (false === $date) {
                        $results['errors'][] = sprintf('Fila %d: Formato de fecha inválido "%s" para el servicio "%s".', $rowCount, $dateStr, $serviceTitle);
                        continue;
                    }
                    $date->setDate($year, $date->format('m'), $date->format('d'));
                    $date->setTime(12, 0); // Set a default time

                    $service = $this->em->getRepository(Service::class)->findOneBy(['title' => $serviceTitle, 'startDate' => $date]);

                    if (!$service) {
                        $service = new Service();
                        $service->setTitle($serviceTitle);
                        $service->setStartDate($date);
                        $service->setEndDate((clone $date)->modify('+'.$hours.' hours'));
                        $this->em->persist($service);
                    }

                    // Check if VolunteerService link exists
                    $volunteerService = $this->em->getRepository(VolunteerService::class)->findOneBy(['volunteer' => $volunteer, 'service' => $service]);
                    if (!$volunteerService) {
                        $volunteerService = new VolunteerService();
                        $volunteerService->setVolunteer($volunteer);
                        $volunteerService->setService($service);
                        $this->em->persist($volunteerService);
                    }

                    // Create Fichaje
                    $fichaje = new Fichaje();
                    $fichaje->setVolunteerService($volunteerService);
                    $fichaje->setStartTime($date);
                    $fichaje->setEndTime((clone $date)->modify('+'.($hours * 3600).' seconds'));
                    $this->em->persist($fichaje);

                    ++$results['successful'];
                    ++$batchCount;

                    if ($batchCount % self::BATCH_SIZE === 0) {
                        $this->em->flush();
                        $this->em->clear(); // Detach all objects from Doctrine
                    }
                } catch (\Exception $e) {
                    $this->logger->error('Error al importar fila: '.$e->getMessage());
                    $results['errors'][] = sprintf('Fila %d: Error procesando el servicio "%s" para "%s" - %s', $rowCount, $serviceTitle, $volunteerName, $e->getMessage());
                }
            }
        }

        fclose($handle);
        $this->em->flush(); // Flush remaining entities

        return $results;
    }
}
