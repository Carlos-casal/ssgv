<?php

namespace App\Controller\Admin;

use App\Service\CsvImportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\Security;
use Symfony\Component\Validator\Constraints\File;

#[Route('/admin')]
#[Security("is_granted('ROLE_ADMIN')")]
class ImportController extends AbstractController
{
    #[Route('/import-hours', name: 'admin_import_hours', methods: ['GET', 'POST'])]
    public function importHours(Request $request, CsvImportService $csvImportService): Response
    {
        $form = $this->createFormBuilder()
            ->add('csvFile', FileType::class, [
                'label' => 'Archivo CSV',
                'required' => true,
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
            ->add('year', NumberType::class, [
                'label' => 'Año de los servicios',
                'required' => true,
                'data' => (int)date('Y'),
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form->get('csvFile')->getData();
            $year = $form->get('year')->getData();

            try {
                $results = $csvImportService->importVolunteerHours($file->getRealPath(), $year);
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
}
