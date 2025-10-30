<?php

namespace App\Service;

use App\Entity\AssistanceConfirmation;
use App\Entity\Fichaje;
use App\Entity\Service;
use App\Entity\Volunteer;
use App\Entity\VolunteerService;
use App\Repository\FichajeRepository;
use App\Repository\ServiceRepository;
use App\Repository\VolunteerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CsvImportService
{
    private EntityManagerInterface $entityManager;
    private VolunteerRepository $volunteerRepository;
    private FichajeRepository $fichajeRepository;
    private ServiceRepository $serviceRepository;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        VolunteerRepository $volunteerRepository,
        FichajeRepository $fichajeRepository,
        ServiceRepository $serviceRepository,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->volunteerRepository = $volunteerRepository;
        $this->fichajeRepository = $fichajeRepository;
        $this->serviceRepository = $serviceRepository;
        $this->logger = $logger;
    }

    /**
     * Imports volunteer hours from a CSV file, creating distinct services based on the 'COMENTARIO' column.
     *
     * @param UploadedFile $file The uploaded CSV file.
     * @return array An array containing success_count, error_count, skipped_count, and errors.
     * @throws \Exception
     */
    public function importVolunteerHours(UploadedFile $file): array
    {
        $results = ['success_count' => 0, 'error_count' => 0, 'skipped_count' => 0, 'errors' => []];
        $fileHandle = fopen($file->getPathname(), 'r');

        if ($fileHandle === false) {
            throw new \Exception("No se pudo abrir el archivo CSV.");
        }

        // === PASS 1: Aggregate data from CSV ===
        $servicesData = [];
        // Read header row and discard
        fgetcsv($fileHandle, 2000, ';');
        $lineNumber = 1;

        while (($row = fgetcsv($fileHandle, 2000, ';')) !== false) {
            $lineNumber++;
            if (empty($row) || empty($row[0])) {
                continue; // Skip empty rows
            }

            $indicativo = trim($row[0]);

            for ($i = 3; $i < count($row); $i += 3) {
                if (!isset($row[$i], $row[$i + 1], $row[$i + 2]) || empty($row[$i]) || empty($row[$i + 1]) || empty($row[$i + 2])) {
                    continue; // Not enough data for a valid entry
                }

                $serviceTitle = trim($row[$i + 2]);
                $dateStr = trim($row[$i + 1]);
                $hoursStr = trim(str_replace(',', '.', $row[$i]));

                // Use a composite key to uniquely identify a service instance
                $serviceKey = $serviceTitle . '|' . $dateStr;

                if (!isset($servicesData[$serviceKey])) {
                    $servicesData[$serviceKey] = [
                        'title' => $serviceTitle,
                        'date_str' => $dateStr,
                        'volunteers' => []
                    ];
                }

                if (!isset($servicesData[$serviceKey]['volunteers'][$indicativo])) {
                    $servicesData[$serviceKey]['volunteers'][$indicativo] = [];
                }

                // Append hours and line number, date is already part of the service instance
                $servicesData[$serviceKey]['volunteers'][$indicativo][] = [
                    'line' => $lineNumber,
                    'hours' => $hoursStr,
                ];
            }
        }
        fclose($fileHandle);


        // === PASS 2: Process aggregated data and create entities ===
        $this->entityManager->beginTransaction();
        try {
            foreach ($servicesData as $serviceKey => $data) {
                if (empty($data['volunteers'])) {
                    continue;
                }

                $serviceTitle = $data['title'];
                $serviceDateStr = $data['date_str'];
                $serviceDate = \DateTime::createFromFormat('d/m/Y', $serviceDateStr);

                if ($serviceDate === false) {
                    $results['errors'][] = sprintf('Formato de fecha no válido "%s" para el servicio "%s". Se omitieron todas las entradas para este servicio.', $serviceDateStr, $serviceTitle);
                    $results['error_count'] += count($data['volunteers']);
                    continue;
                }

                $serviceStartDate = (clone $serviceDate)->setTime(0, 0, 0);

                // Find or create the Service based on title AND exact start date
                $service = $this->serviceRepository->findOneBy(['title' => $serviceTitle, 'startDate' => $serviceStartDate]);

                if (!$service) {
                    $service = new Service();
                    $service->setTitle($serviceTitle);
                    $service->setDescription('Servicio generado automáticamente desde la importación de CSV.');
                    $service->setType('evento'); // Default values
                    $service->setCategory('asistencia_social'); // Default values
                    $service->setStartDate($serviceStartDate);
                    $service->setEndDate((clone $serviceDate)->setTime(23, 59, 59)); // Service is for a single day
                    $this->entityManager->persist($service);
                }

                foreach ($data['volunteers'] as $indicativo => $entries) {
                    $volunteer = $this->volunteerRepository->findOneBy(['indicativo' => $indicativo]);

                    if (!$volunteer) {
                        $results['errors'][] = sprintf('No se encontró al voluntario con indicativo "%s" para el servicio "%s" en la fecha %s. Se omitieron %d fichajes.', $indicativo, $serviceTitle, $serviceDateStr, count($entries));
                        $results['error_count'] += count($entries);
                        continue;
                    }

                    // Create single AssistanceConfirmation and VolunteerService per volunteer-service pair
                    $assistanceConfirmation = new AssistanceConfirmation();
                    $assistanceConfirmation->setVolunteer($volunteer);
                    $assistanceConfirmation->setService($service);
                    $assistanceConfirmation->setStatus(AssistanceConfirmation::STATUS_ATTENDING);
                    $this->entityManager->persist($assistanceConfirmation);

                    $volunteerService = new VolunteerService();
                    $volunteerService->setVolunteer($volunteer);
                    $volunteerService->setService($service);
                    $this->entityManager->persist($volunteerService);

                    foreach ($entries as $entry) {
                        $horas = filter_var($entry['hours'], FILTER_VALIDATE_FLOAT);

                        if ($horas === false || $horas <= 0) {
                            $results['errors'][] = sprintf('Línea %d: Dato de horas no válido para el voluntario %s en servicio "%s" (fecha: "%s", horas: "%s").', $entry['line'], $indicativo, $serviceTitle, $serviceDateStr, $entry['hours']);
                            $results['error_count']++;
                            continue;
                        }

                        // Use the service's date for the fichaje
                        $startTime = (clone $serviceDate)->setTime(0, 0, 0);
                        $seconds = (int)($horas * 3600);
                        $endTime = (clone $startTime)->add(new \DateInterval('PT' . $seconds . 'S'));

                        // Check for duplicates
                        if ($this->fichajeRepository->existsForVolunteerInDateRange($volunteer, $startTime, $endTime)) {
                            $results['skipped_count']++;
                            continue;
                        }

                        $fichaje = new Fichaje();
                        $fichaje->setVolunteerService($volunteerService);
                        $fichaje->setStartTime($startTime);
                        $fichaje->setEndTime($endTime);
                        // Note is not needed as it's implicit in the service title
                        $this->entityManager->persist($fichaje);

                        $results['success_count']++;
                    }
                }
            }

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            $this->logger->error('Error en la importación de CSV: ' . $e->getMessage(), ['exception' => $e]);
            $results['errors'][] = 'Ocurrió un error inesperado durante la importación. La operación fue cancelada. Detalles: ' . $e->getMessage();
        }

        return $results;
    }
}
