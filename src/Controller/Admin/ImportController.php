<?php

namespace App\Controller\Admin;

use App\Entity\Fichaje;
use App\Entity\Service;
use App\Entity\Volunteer;
use App\Entity\VolunteerService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\Security;
use Symfony\Component\Validator\Constraints\File;

#[Route('/admin')]
#[Security("is_granted('ROLE_ADMIN')")]
class ImportController extends AbstractController
{
    private const BATCH_SIZE = 50;

    #[Route('/import-hours', name: 'admin_import_hours', methods: ['GET', 'POST'])]
    public function importHours(Request $request, EntityManagerInterface $em, LoggerInterface $logger): Response
    {
        $form = $this->createFormBuilder()
            ->add('csvFile', FileType::class, [
                'label' => 'Archivo CSV',
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['text/csv', 'text/plain'],
                        'mimeTypesMessage' => 'Por favor, sube un archivo CSV válido.',
                    ])
                ],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form->get('csvFile')->getData();

            try {
                $results = $this->importCsvData($file->getRealPath(), $em, $logger);
                $this->addFlash('success', sprintf('%d registros importados correctamente.', $results['successful']));
                if (!empty($results['errors'])) {
                    foreach ($results['errors'] as $error) {
                        $this->addFlash('danger', $error);
                    }
                }
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Error al importar el archivo: ' . $e->getMessage());
            }

            return $this->redirectToRoute('admin_import_hours');
        }

        return $this->render('admin/import/import_hours.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function importCsvData(string $filePath, EntityManagerInterface $em, LoggerInterface $logger): array
    {
        $handle = fopen($filePath, 'r');
        if (false === $handle) {
            throw new \RuntimeException('No se pudo abrir el archivo.');
        }

        $headers = fgetcsv($handle, 0, ';');
        if (false === $headers) {
            fclose($handle);
            throw new \RuntimeException('No se pudo leer la cabecera del CSV.');
        }

        if (isset($headers[0]) && str_starts_with($headers[0], "\xEF\xBB\xBF")) {
            $headers[0] = substr($headers[0], 3);
        }

        $results = ['successful' => 0, 'errors' => []];
        $rowCount = 1;
        $batchCount = 0;

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $rowCount++;
            $row = array_pad($row, \count($headers), null);
            $data = array_combine($headers, $row);

            $volunteerName = trim($data['VOLUNTARIO'] ?? '');
            if (empty($volunteerName)) {
                continue;
            }

            $volunteer = $em->getRepository(Volunteer::class)
                ->createQueryBuilder('v')
                ->where('LOWER(TRIM(REGEXP_REPLACE(CONCAT(v.name, \' \', v.lastName), \'[[:space:]]+\', \' \'))) = LOWER(TRIM(REGEXP_REPLACE(:volunteerName, \'[[:space:]]+\', \' \')))')
                ->setParameter('volunteerName', $volunteerName)
                ->getQuery()
                ->getOneOrNullResult();

            if (!$volunteer) {
                $results['errors'][] = sprintf('Fila %d: No se encontró al voluntario "%s".', $rowCount, $volunteerName);
                continue;
            }

            for ($i = 1; isset($data['HORAS_'.$i]); ++$i) {
                $hoursStr = trim($data['HORAS_'.$i] ?? '');
                $dateStr = trim($data['FECHA_'.$i] ?? '');
                $serviceTitle = trim($data['COMENTARIO_'.$i] ?? '');

                if (empty($hoursStr) || empty($dateStr) || empty($serviceTitle) || strtolower($hoursStr) === 'total') {
                    continue;
                }

                $hours = (float) str_replace(',', '.', $hoursStr);
                if ($hours <= 0) {
                    continue;
                }

                try {
                    $date = \DateTime::createFromFormat('d/m/Y', $dateStr);
                    if (false === $date) {
                         $date = \DateTime::createFromFormat('d-m-Y', $dateStr);
                    }

                    if (false === $date) {
                        $results['errors'][] = sprintf('Fila %d: Formato de fecha inválido "%s" para el servicio "%s". Se esperaba dd/mm/aaaa o dd-mm-aaaa.', $rowCount, $dateStr, $serviceTitle);
                        continue;
                    }
                    $date->setTime(12, 0, 0);

                    $service = $em->getRepository(Service::class)->findOneBy(['title' => $serviceTitle, 'startDate' => $date]);
                    if (!$service) {
                        $service = new Service();
                        $service->setTitle($serviceTitle);
                        $service->setStartDate($date);
                        $service->setEndDate((clone $date)->modify('+'.$hours.' hours'));
                        $em->persist($service);
                    }

                    $volunteerService = $em->getRepository(VolunteerService::class)->findOneBy(['volunteer' => $volunteer, 'service' => $service]);
                    if (!$volunteerService) {
                        $volunteerService = new VolunteerService($volunteer, $service);
                        $em->persist($volunteerService);
                    }

                    $fichaje = new Fichaje();
                    $fichaje->setVolunteerService($volunteerService);
                    $fichaje->setStartTime($date);
                    $fichaje->setEndTime((clone $date)->modify('+'.($hours * 3600).' seconds'));
                    $em->persist($fichaje);

                    ++$results['successful'];
                    if (++$batchCount % self::BATCH_SIZE === 0) {
                        $em->flush();
                        $em->clear();
                    }
                } catch (\Exception $e) {
                    $logger->error('Error al importar fila: '.$e->getMessage(), ['row' => $rowCount]);
                    $results['errors'][] = sprintf('Fila %d: Error procesando el servicio "%s" para "%s" - %s', $rowCount, $serviceTitle, $volunteerName, $e->getMessage());
                }
            }
        }

        fclose($handle);
        $em->flush();

        return $results;
    }
}
