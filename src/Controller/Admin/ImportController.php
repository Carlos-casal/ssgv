<?php

namespace App\Controller\Admin;

use App\Service\CsvImportService;
use App\Service\CsvImportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends AbstractController
{
    #[Route('/admin/import-hours', name: 'admin_import_hours')]
    public function index(Request $request, CsvImportService $csvImportService): Response
    {
        if ($request->isMethod('POST')) {
            $file = $request->files->get('csv_file');
            $year = $request->request->get('year');

            if ($file && $year) {
                try {
                    $results = $csvImportService->import($file->getRealPath(), $year);
                    $this->addFlash('success', $results['successful_imports'] . ' registros importados correctamente.');
                    if (!empty($results['errors'])) {
                        foreach ($results['errors'] as $error) {
                            $this->addFlash('danger', $error);
                        }
                    }
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Error al importar el archivo: ' . $e->getMessage());
                }
            } else {
                $this->addFlash('danger', 'Por favor, sube un archivo CSV y selecciona un año.');
            }

            return $this->redirectToRoute('admin_import_hours');
        }

        return $this->render('admin/import/index.html.twig');
    }
}