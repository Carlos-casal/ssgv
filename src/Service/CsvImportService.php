<?php

namespace App\Service;

use App\Entity\Fichaje;
use App\Entity\Service;
use App\Entity\Volunteer;
use App\Entity\VolunteerService;
use App\Repository\VolunteerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CsvImportService
{
    private EntityManagerInterface $entityManager;
    private VolunteerRepository $volunteerRepository;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        VolunteerRepository $volunteerRepository,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->volunteerRepository = $volunteerRepository;
        $this->logger = $logger;
    }

    /**
     * Imports volunteer hours from a CSV file.
     *
     * @param UploadedFile $file The uploaded CSV file.
     * @return array An array containing success_count, error_count, and errors.
     * @throws \Exception
     */
    public function importVolunteerHours(UploadedFile $file): array
    {
        $results = ['success_count' => 0, 'error_count' => 0, 'errors' => []];
        $fileHandle = fopen($file->getPathname(), 'r');

        if ($fileHandle === false) {
            throw new \Exception("No se pudo abrir el archivo CSV.");
        }

        // Pre-scan to find the date range for the service
        $minDate = null;
        $maxDate = null;
        while (($row = fgetcsv($fileHandle, 2000, ';')) !== false) {
            for ($i = 4; $i < count($row); $i += 3) {
                if (!empty($row[$i])) {
                    $date = \DateTime::createFromFormat('d/m/Y', $row[$i]);
                    if ($date) {
                        if ($minDate === null || $date < $minDate) {
                            $minDate = clone $date;
                        }
                        if ($maxDate === null || $date > $maxDate) {
                            $maxDate = clone $date;
                        }
                    }
                }
            }
        }

        if ($minDate === null || $maxDate === null) {
            $results['errors'][] = 'No se encontraron fechas válidas en el archivo CSV.';
            fclose($fileHandle);
            return $results;
        }

        rewind($fileHandle); // Go back to the beginning of the file

        $service = new Service();
        $service->setTitle('Horas Importadas - ' . date('Y-m-d H:i:s'));
        $service->setStartDate((clone $minDate)->setTime(0, 0));
        $service->setEndDate((clone $maxDate)->setTime(23, 59, 59));
        $service->setDescription('Servicio generado automáticamente para la importación de horas desde CSV.');

        $this->entityManager->persist($service);

        $this->entityManager->beginTransaction();
        try {
            // Read header row and discard
            fgetcsv($fileHandle, 2000, ';');
            $lineNumber = 1;

            while (($row = fgetcsv($fileHandle, 2000, ';')) !== false) {
                $lineNumber++;
                if (empty($row) || empty($row[0])) {
                    continue; // Skip empty rows
                }

                $indicativo = trim($row[0]);
                $volunteer = $this->volunteerRepository->findOneBy(['indicativo' => $indicativo]);

                if (!$volunteer) {
                    $results['errors'][] = sprintf('Línea %d: No se encontró al voluntario con indicativo "%s". Se omitieron todas sus horas.', $lineNumber, $indicativo);
                    // We need to count how many hour entries this row had to correctly increment error_count
                    for ($i = 3; $i < count($row); $i += 3) {
                        if (!empty($row[$i]) && !empty($row[$i + 1])) {
                             $results['error_count']++;
                        }
                    }
                    continue;
                }

                $volunteerService = new VolunteerService();
                $volunteerService->setVolunteer($volunteer);
                $volunteerService->setService($service);
                $this->entityManager->persist($volunteerService);

                for ($i = 3; $i < count($row); $i += 3) {
                    // Check if the essential columns for a record exist and are not empty
                    if (!isset($row[$i], $row[$i+1]) || empty($row[$i]) || empty($row[$i+1])) {
                        continue; // No more hour data in this row
                    }

                    $horasStr = trim(str_replace(',', '.', $row[$i]));
                    $fechaStr = trim($row[$i+1]);
                    $comentario = isset($row[$i+2]) ? trim($row[$i+2]) : '';

                    $horas = filter_var($horasStr, FILTER_VALIDATE_FLOAT);
                    $date = \DateTime::createFromFormat('d/m/Y', $fechaStr);

                    if ($horas === false || $horas <= 0 || $date === false) {
                        $results['errors'][] = sprintf('Línea %d: Datos de fichaje no válidos para el voluntario %s (fecha: "%s", horas: "%s").', $lineNumber, $indicativo, $fechaStr, $horasStr);
                        $results['error_count']++;
                        continue;
                    }

                    $startTime = (clone $date)->setTime(0, 0, 0);
                    $seconds = (int)($horas * 3600);
                    $endTime = (clone $startTime)->add(new \DateInterval('PT'.$seconds.'S'));

                    $fichaje = new Fichaje();
                    $fichaje->setVolunteerService($volunteerService);
                    $fichaje->setStartTime($startTime);
                    $fichaje->setEndTime($endTime);
                    $fichaje->setNotes($comentario);
                    $this->entityManager->persist($fichaje);

                    $results['success_count']++;
                }
            }

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            $this->logger->error('Error en la importación de CSV: ' . $e->getMessage(), ['exception' => $e]);
            $results['errors'][] = 'Ocurrió un error inesperado durante la importación. La operación fue cancelada. Detalles: ' . $e->getMessage();
        } finally {
            fclose($fileHandle);
        }

        return $results;
    }
}
