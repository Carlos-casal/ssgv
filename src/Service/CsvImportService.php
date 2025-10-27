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

                if (!isset($servicesData[$serviceTitle])) {
                    $servicesData[$serviceTitle] = ['min_date' => null, 'max_date' => null, 'volunteers' => []];
                }

                $date = \DateTime::createFromFormat('d/m/Y', $dateStr);
                if ($date) {
                    if ($servicesData[$serviceTitle]['min_date'] === null || $date < $servicesData[$serviceTitle]['min_date']) {
                        $servicesData[$serviceTitle]['min_date'] = clone $date;
                    }
                    if ($servicesData[$serviceTitle]['max_date'] === null || $date > $servicesData[$serviceTitle]['max_date']) {
                        $servicesData[$serviceTitle]['max_date'] = clone $date;
                    }
                }

                if (!isset($servicesData[$serviceTitle]['volunteers'][$indicativo])) {
                    $servicesData[$serviceTitle]['volunteers'][$indicativo] = [];
                }

                $servicesData[$serviceTitle]['volunteers'][$indicativo][] = [
                    'line' => $lineNumber,
                    'hours' => $hoursStr,
                    'date' => $dateStr,
                ];
            }
        }
        fclose($fileHandle);


        // === PASS 2: Process aggregated data and create entities ===
        $this->entityManager->beginTransaction();
        try {
            foreach ($servicesData as $serviceTitle => $data) {
                if (empty($data['volunteers'])) {
                    continue;
                }

                // Find or create the Service
                $service = $this->serviceRepository->findOneBy(['title' => $serviceTitle]);
                if (!$service) {
                    $service = new Service();
                    $service->setTitle($serviceTitle);
                    $service->setDescription('Servicio generado automáticamente desde la importación de CSV.');
                    $service->setType('evento'); // Default values
                    $service->setCategory('asistencia_social'); // Default values
                }
                // Update dates to encompass all entries for this service
                if ($data['min_date']) {
                    $service->setStartDate((clone $data['min_date'])->setTime(0, 0));
                }
                if ($data['max_date']) {
                    $service->setEndDate((clone $data['max_date'])->setTime(23, 59, 59));
                }
                $this->entityManager->persist($service);


                foreach ($data['volunteers'] as $indicativo => $entries) {
                    $volunteer = $this->volunteerRepository->findOneBy(['indicativo' => $indicativo]);

                    if (!$volunteer) {
                        $results['errors'][] = sprintf('No se encontró al voluntario con indicativo "%s" para el servicio "%s". Se omitieron %d horas.', $indicativo, $serviceTitle, count($entries));
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
                        $date = \DateTime::createFromFormat('d/m/Y', $entry['date']);

                        if ($horas === false || $horas <= 0 || $date === false) {
                            $results['errors'][] = sprintf('Línea %d: Datos de fichaje no válidos para el voluntario %s en servicio "%s" (fecha: "%s", horas: "%s").', $entry['line'], $indicativo, $serviceTitle, $entry['date'], $entry['hours']);
                            $results['error_count']++;
                            continue;
                        }

                        $startTime = (clone $date)->setTime(0, 0, 0);
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
                        // The note is the service title itself, no need to set it again.
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
