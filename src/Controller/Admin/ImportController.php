<?php

namespace App\Controller\Admin;

use App\Service\CsvImportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Route('/admin/import-hours')]
class ImportController extends AbstractController
{
    #[Route('/', name: 'app_admin_import_hours', methods: ['GET', 'POST'])]
    public function index(Request $request, CsvImportService $csvImportService): Response
    {
        $form = $this->createFormBuilder()
            ->add('year', TextType::class, [
                'label' => 'Año de los servicios',
                'data' => date('Y'),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('csv_file', FileType::class, [
                'label' => 'Archivo CSV',
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'text/csv',
                            'text/plain',
                        ],
                        'mimeTypesMessage' => 'Por favor, sube un archivo CSV válido.',
                    ])
                ],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('csv_file')->getData();
            $year = $form->get('year')->getData();

            if ($csvFile) {
                $report = $csvImportService->import($csvFile, $year);

                if ($report['success'] > 0) {
                    $this->addFlash('success', "¡Importación completada! Se han añadido {$report['success']} nuevos registros de horas para el año {$year}.");
                }

                if ($report['skipped'] > 0) {
                    $this->addFlash('info', "Se omitieron {$report['skipped']} registros porque ya existían.");
                }

                if (!empty($report['errors'])) {
                    $this->addFlash('danger', 'La importación ha fallado para algunos registros. Por favor, revisa los siguientes errores:');
                    foreach ($report['errors'] as $error) {
                        $this->addFlash('danger', $error);
                    }
                }

                if ($report['success'] == 0 && empty($report['errors']) && $report['skipped'] == 0) {
                    $this->addFlash('warning', 'El archivo ha sido procesado, pero no se ha importado ningún registro nuevo. Revisa el formato del archivo o que no esté vacío.');
                }
            }

            return $this->redirectToRoute('app_admin_import_hours');
        }

        return $this->render('admin/import/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
