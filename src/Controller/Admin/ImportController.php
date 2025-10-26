<?php

namespace App\Controller\Admin;

use App\Service\CsvImportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

#[Route('/admin/import')]
class ImportController extends AbstractController
{
    #[Route('/hours', name: 'admin_import_hours')]
    public function importHours(Request $request, CsvImportService $csvImportService): Response
    {
        $form = $this->createFormBuilder()
            ->add('csv_file', FileType::class, [
                'label' => 'CSV file',
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'text/csv',
                            'text/plain',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid CSV document',
                    ])
                ],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('csv_file')->getData();

            if ($file) {
                $results = $csvImportService->import($file);

                $this->addFlash('success', "Successfully imported {$results['success']} records.");
                if (!empty($results['errors'])) {
                    foreach ($results['errors'] as $error) {
                        $this->addFlash('danger', $error);
                    }
                }

                return $this->redirectToRoute('admin_import_hours');
            }
        }

        return $this->render('admin/import/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
