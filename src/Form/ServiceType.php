<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType; // Para fecha_limite_inscripcion
use Symfony\Component\Form\Extension\Core\Type\TimeType;   // Para hora_base y hora_salida
use Symfony\Component\Form\Extension\Core\Type\IntegerType; // Para maximo_asistentes
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; // Para tipo y categoría
use Symfony\Component\Form\Extension\Core\Type\CollectionType; // Si necesitas para destinatarios
use Symfony\Component\Form\Extension\Core\Type\CheckboxType; // Para los destinatarios individuales
// Si tienes la propiedad 'eys' o similar:
use Symfony\Component\Form\Extension\Core\Type\HiddenType; // Ejemplo si 'eys' es un campo interno o auto-rellenado


class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeration', TextType::class, [
                'label' => 'Numeración',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: S-2025-001'],
            ])
            ->add('title', TextType::class, [
                'label' => 'Título del Servicio',
                'required' => true,
                'attr' => ['placeholder' => 'Ej: Recogida de Alimentos'],
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Fecha y Hora de Inicio',
                'widget' => 'single_text', // Muestra un único input para fecha y hora
                'required' => true,
                'html5' => true, // Habilita los tipos de input HTML5 para fecha/hora
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Fecha y Hora de Fin',
                'widget' => 'single_text',
                'required' => true,
                'html5' => true,
            ])
            ->add('registrationLimitDate', DateType::class, [ // Usamos DateType porque es solo fecha
                'label' => 'Fecha Límite de Inscripción',
                'widget' => 'single_text',
                'required' => false,
                'html5' => true,
            ])
            ->add('timeAtBase', TimeType::class, [
                'label' => 'Hora en Base',
                'widget' => 'single_text',
                'required' => false,
                'html5' => true,
            ])
            ->add('departureTime', TimeType::class, [
                'label' => 'Hora de Salida',
                'widget' => 'single_text',
                'required' => false,
                'html5' => true,
            ])
            ->add('maxAttendees', IntegerType::class, [
                'label' => 'Máximo Asistentes',
                'required' => false,
                'attr' => ['min' => 1, 'placeholder' => 'Ej: 50'],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Tipo de Servicio',
                'choices' => [
                    'Evento' => 'evento',
                    'Formación' => 'formacion',
                    'Campaña' => 'campana',
                    // Agrega más tipos si es necesario
                ],
                'placeholder' => 'Selecciona un tipo',
                'required' => false,
            ])

            ->add('slug', TextType::class, [
                'label' => 'Slug (generado automáticamente)',
                'required' => false, // No es requerido por el usuario, lo genera Gedmo
                'disabled' => true, // El usuario no puede editarlo
                // O también puedes usar:
                // 'attr' => ['readonly' => true],
            ])
            
            ->add('category', ChoiceType::class, [
                'label' => 'Categoría del Servicio',
                'choices' => [
                    'Asistencia Social' => 'asistencia_social',
                    'Medio Ambiente' => 'medio_ambiente',
                    'Rescate' => 'rescate',
                    'Educación' => 'educacion',
                    // Agrega más categorías si es necesario
                ],
                'placeholder' => 'Selecciona una categoría',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
                'attr' => ['rows' => 5, 'placeholder' => 'Detalles del servicio...'],
            ])
            // Para 'Enviar a destinatario': asumiendo que son opciones estáticas por ahora
            ->add('recipients', ChoiceType::class, [
                'label' => 'Enviar a Destinatario',
                'choices' => [
                    'Voluntarios Activos' => 'voluntarios_activos',
                    'Nuevos Voluntarios' => 'nuevos_voluntarios',
                    'Junta Directiva' => 'junta_directiva',
                    // Agrega más opciones de destinatarios si es necesario
                ],
                'multiple' => true,  // Permite seleccionar múltiples opciones
                'expanded' => true,  // Renderiza como checkboxes
                'required' => false,
                'help' => 'Selecciona a quién deseas enviar la información del servicio.',
            ])
            ->add('collaboration_with_other_services', CheckboxType::class, [
                'label' => 'Colaboración con otros servicios de emergencias',
                'required' => false,
            ])
            ->add('locality', TextType::class, [
                'label' => 'Localidad',
                'required' => false,
            ])
            ->add('requester', TextType::class, [
                'label' => 'Solicitante',
                'required' => false,
            ])
            ->add('assistanceConfirmations', CollectionType::class, [
                'entry_type' => AssistanceConfirmationType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}