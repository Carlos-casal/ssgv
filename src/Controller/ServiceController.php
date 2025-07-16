<?php

namespace App\Controller;

use App\Entity\Service; // Asegúrate de que esta línea exista para la entidad Service
use App\Form\ServiceType; // Asegúrate de que esta línea exista para el formulario ServiceType
use App\Repository\ServiceRepository; // ¡Importante! Necesitamos el repositorio para listar servicios
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // Usamos Annotation\Route como en tu archivo existente

class ServiceController extends AbstractController
{
    /**
     * Acción para listar todos los servicios.
     * Esta es la nueva acción que acabamos de crear.
     */
    #[Route('/servicios', name: 'app_services_list', methods: ['GET'])]
    public function listServices(ServiceRepository $serviceRepository): Response
    {
        // Obtiene todos los servicios de la base de datos
        $services = $serviceRepository->findAll();

        // Renderiza la plantilla Twig y le pasa los servicios
        return $this->render('service/list_service.html.twig', [
            'services' => $services,
        ]);
    }
    /**
     * Acción para crear un nuevo servicio.
     * Esta es tu acción 'new' existente.
     */
    #[Route('nuevo_servicio', name: 'app_service_new', methods: ['GET', 'POST'])] // Añadí 'methods' para mayor claridad
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // 1. Crear una nueva instancia de la entidad Service
        $service = new Service();

        // 2. Crear una instancia del formulario, vinculándola a la entidad $service
        $form = $this->createForm(ServiceType::class, $service);

        // 3. Manejar la petición (leer los datos enviados por el formulario)
        $form->handleRequest($request);

        // 4. Comprobar si el formulario ha sido enviado y es válido
        if ($form->isSubmitted() && $form->isValid()) {
            // Los datos del formulario ya están en la entidad $service gracias a handleRequest()

            // 5. Persistir la entidad en la base de datos
            $entityManager->persist($service);
            $entityManager->flush();

            // Opcional: Añadir un mensaje flash para confirmar la creación
            $this->addFlash('success', '¡El servicio ha sido creado con éxito!');

            // 6. Redirigir a la lista de servicios después de crear uno nuevo
            // Usamos 'list_service' que es el nombre de la nueva ruta
            return $this->redirectToRoute('app_services_list');
        }

        // 7. Renderizar la plantilla Twig, pasando el formulario
        return $this->render('service/new_service.html.twig', [
            'serviceForm' => $form->createView(), // Usa createView() para pasar el formulario a Twig
        ]);
    }

    
    #[Route('/servicios/{id}', name: 'app_service_show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        $form = $this->createForm(ServiceType::class, $service);

        return $this->render('service/show_service.html.twig', [
            'service' => $service,
            'serviceForm' => $form->createView(),
        ]);
    }

    #[Route('/servicios/{id}/editar', name: 'app_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Servicio actualizado correctamente.');

            return $this->redirectToRoute('app_service_edit', ['id' => $service->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service/edit_service.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
            'services_attendance' => $service->getVolunteerServices(),
        ]);
    }
}
