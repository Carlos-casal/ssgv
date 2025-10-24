<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

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
                'label' => 'Título',
                'required' => true,
                'attr' => ['placeholder' => 'Ej: Recogida de Alimentos'],
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Inicio',
                'widget' => 'single_text',
                'required' => true,
                'html5' => true,
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Fin',
                'widget' => 'single_text',
                'required' => true,
                'html5' => true,
            ])
            ->add('registrationLimitDate', DateType::class, [
                'label' => 'Límite',
                'widget' => 'single_text',
                'required' => true,
                'html5' => true,
            ])
            ->add('timeAtBase', TimeType::class, [
                'label' => 'Base',
                'widget' => 'single_text',
                'required' => true,
                'html5' => true,
            ])
            ->add('departureTime', TimeType::class, [
                'label' => 'Salida',
                'widget' => 'single_text',
                'required' => true,
                'html5' => true,
            ])
            ->add('maxAttendees', IntegerType::class, [
                'label' => 'Asistentes',
                'required' => false,
                'attr' => ['min' => 1, 'placeholder' => 'Ej: 50'],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Tipo',
                'choices' => [
                    'Evento' => 'evento',
                    'Formación' => 'formacion',
                    'Campaña' => 'campana',
                ],
                'placeholder' => 'Selecciona un tipo',
                'required' => true,
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Categoría',
                'choices' => [
                    'Asistencia Social' => 'asistencia_social',
                    'Medio Ambiente' => 'medio_ambiente',
                    'Rescate' => 'rescate',
                    'Educación' => 'educacion',
                ],
                'placeholder' => 'Selecciona una categoría',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('recipients', ChoiceType::class, [
                'label' => 'Destinatarios del Servicio',
                'choices' => [
                    'Niños y adolescentes' => 'ninos_adolescentes',
                    'Personas mayores' => 'personas_mayores',
                    'Población en general' => 'poblacion_general',
                    'Personas con discapacidad' => 'personas_discapacidad',
                    'Animales' => 'animales',
                    'Medio ambiente' => 'medio_ambiente',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('locality', TextType::class, [
                'label' => 'Lugar',
                'required' => false,
            ])
            ->add('afluencia', ChoiceType::class, [
                'label' => 'Afluencia',
                'choices' => [
                    'Baja' => 'baja',
                    'Media' => 'media',
                    'Alta' => 'alta',
                ],
                'placeholder' => 'Selecciona un nivel',
                'required' => false,
            ])
            ->add('numSvb', IntegerType::class, [
                'label' => 'SVB',
                'required' => false,
            ])
            ->add('numSva', IntegerType::class, [
                'label' => 'SVA',
                'required' => false,
            ])
            ->add('numSvae', IntegerType::class, [
                'label' => 'SVAE',
                'required' => false,
            ])
            ->add('numDoctors', IntegerType::class, [
                'label' => 'Medico',
                'required' => false,
            ])
            ->add('numNurses', HiddenType::class, [
                'required' => false,
            ])
            ->add('numDues', IntegerType::class, [
                'label' => 'Enfermeros/as (DUE)',
                'required' => false,
                'mapped' => false,
            ])
            ->add('numTecnicos', IntegerType::class, [
                'label' => 'Técnicos (TTS)',
                'required' => false,
                'mapped' => false,
            ])
            ->add('hasFieldHospital', CheckboxType::class, [
                'label' => 'Hospital de Campaña',
                'required' => false,
            ])
            ->add('tasks', TextareaType::class, [
                'label' => 'Tareas',
                'required' => false,
            ])
            ->add('hasProvisions', CheckboxType::class, [
                'label' => 'Avituallamiento',
                'required' => false,
            ])
            ->add('whatsappMessage', TextareaType::class, [
                'label' => 'Mensaje de WhatsApp',
                'required' => false,
                'attr' => ['rows' => 8, 'class' => 'whatsapp-message-textarea'],
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            $numDues = $data['numDues'] ?? 0;
            $numTecnicos = $data['numTecnicos'] ?? 0;

            $data['numNurses'] = $numDues + $numTecnicos;

            $event->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}