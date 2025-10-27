<?php

namespace App\Controller\Admin;

use App\Service\CsvImportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class ImportController extends AbstractController
{
    #[Route('/import/hours', name: 'app_admin_import_hours', methods: ['GET', 'POST'])]
    public function importHours(Request $request, CsvImportService $csvImportService): Response
    {
        $form = $this->createFormBuilder()
            ->add('file', \Symfony\Component\Form\Extension\Core\Type\FileType::class, [
                'label' => 'Archivo CSV',
                'required' => true,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();

            if ($file) {
                try {
                    $results = $csvImportService->importVolunteerHours($file);
                    $this->addFlash('success', sprintf(
                        'Importación completada. %d registros procesados, %d errores, %d omitidos (duplicados).',
                        $results['success_count'],
                        $results['error_count'],
                        $results['skipped_count'] ?? 0
                    ));

                    if (!empty($results['errors'])) {
                        foreach ($results['errors'] as $error) {
                            $this->addFlash('danger', $error);
                        }
                    }
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Error durante la importación: ' . $e->getMessage());
                }
            }

            return $this->redirectToRoute('app_admin_import_hours');
        }

        return $this->render('admin/import/hours.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
