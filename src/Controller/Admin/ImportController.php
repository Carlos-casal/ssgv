<?php

namespace App\Controller\Admin;

use App\Form\ImportHoursType;
use App\Service\CsvImportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/import-hours')]
class ImportController extends AbstractController
{
    #[Route('/', name: 'app_admin_import_hours', methods: ['GET', 'POST'])]
    public function index(Request $request, CsvImportService $csvImportService): Response
    {
        $form = $this->createForm(ImportHoursType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('csv_file')->getData();
            if ($csvFile) {
                $report = $csvImportService->import($csvFile);

                if ($report['success'] > 0) {
                    $this->addFlash('success', "Se importaron {$report['success']} registros de horas con éxito.");
                }

                if (!empty($report['errors'])) {
                    foreach ($report['errors'] as $error) {
                        $this->addFlash('danger', $error);
                    }
                }
            }

            return $this->redirectToRoute('app_admin_import_hours');
        }

        return $this->render('admin/import/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
