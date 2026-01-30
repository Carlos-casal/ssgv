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
use App\Entity\ServiceCategory;
use App\Entity\ServiceSubcategory;
use App\Entity\Vehicle;
use App\Entity\ServiceType as EntityServiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Form type for creating and editing Service entities.
 * This form includes all fields necessary to define a service, from basic details to resource requirements.
 */
class ServiceFormType extends AbstractType
{
    /**
     * Builds the form structure for the Service entity.
     *
     * This method defines all the fields for the form. It also includes a PRE_SUBMIT event listener
     * to combine the 'numDues' and 'numTecnicos' fields into the 'numNurses' field before submission,
     * as 'numDues' and 'numTecnicos' are for user interface purposes only and are not mapped to the entity.
     *
     * @param FormBuilderInterface $builder The form builder.
     * @param array $options The options for building the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                if (isset($data['type']) && $data['type']) {
                    $form->add('subcategory', EntityType::class, [
                        'class' => ServiceSubcategory::class,
                        'choice_label' => function(ServiceSubcategory $sub) {
                            return $sub->getCode() ? $sub->getCode() . ' ' . $sub->getName() : $sub->getName();
                        },
                        'group_by' => function(ServiceSubcategory $sub) {
                            $cat = $sub->getCategory();
                            return $cat->getCode() ? $cat->getCode() . ' ' . $cat->getName() : $cat->getName();
                        },
                        'label' => 'Categoría / Subcategoría',
                        'placeholder' => 'Selecciona subcategoría...',
                        'required' => true,
                        'attr' => ['class' => 'form-select select-hierarchy']
                    ]);
                }
            })
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
            ->add('type', EntityType::class, [
                'class' => EntityServiceType::class,
                'choice_label' => function(EntityServiceType $type) {
                    return $type->getCode() ? $type->getCode() . '. ' . $type->getName() : $type->getName();
                },
                'label' => 'Tipo',
                'placeholder' => 'Selecciona un tipo',
                'required' => true,
            ])
            ->add('subcategory', EntityType::class, [
                'class' => ServiceSubcategory::class,
                'choice_label' => function(ServiceSubcategory $sub) {
                    return $sub->getCode() ? $sub->getCode() . ' ' . $sub->getName() : $sub->getName();
                },
                'group_by' => function(ServiceSubcategory $sub) {
                    $cat = $sub->getCategory();
                    return $cat->getCode() ? $cat->getCode() . ' ' . $cat->getName() : $cat->getName();
                },
                'label' => 'Categoría / Subcategoría',
                'placeholder' => 'Selecciona subcategoría...',
                'required' => true,
                'choices' => [], // Started empty to be populated via JS
                'attr' => ['class' => 'form-select select-hierarchy']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('locality', TextType::class, [
                'label' => 'Lugar',
                'required' => true,
            ])
            ->add('afluencia', ChoiceType::class, [
                'label' => 'Afluencia',
                'choices' => [
                    'Baja' => 'baja',
                    'Media' => 'media',
                    'Alta' => 'alta',
                ],
                'choice_attr' => [
                    'Baja' => ['class' => 'bg-green-100 text-green-800'],
                    'Media' => ['class' => 'bg-orange-100 text-orange-800'],
                    'Alta' => ['class' => 'bg-blue-100 text-blue-800'],
                ],
                'placeholder' => 'Selecciona un nivel',
                'required' => true,
            ])
            ->add('numSvb', IntegerType::class, [
                'label' => 'SVB',
                'required' => false,
                'attr' => ['min' => 0, 'max' => 99, 'maxlength' => 2, 'style' => 'width: 60px;'],
            ])
            ->add('numSva', IntegerType::class, [
                'label' => 'SVA',
                'required' => false,
                'attr' => ['min' => 0, 'max' => 99, 'maxlength' => 2, 'style' => 'width: 60px;'],
            ])
            ->add('numSvae', IntegerType::class, [
                'label' => 'SVAE',
                'required' => false,
                'attr' => ['min' => 0, 'max' => 99, 'maxlength' => 2, 'style' => 'width: 60px;'],
            ])
            ->add('numVir', IntegerType::class, [
                'label' => 'VIR',
                'required' => false,
                'attr' => ['min' => 0, 'max' => 99, 'maxlength' => 2, 'style' => 'width: 60px;'],
            ])
            ->add('numTes', IntegerType::class, [
                'label' => 'TES',
                'required' => false,
                'attr' => ['min' => 0, 'max' => 99, 'maxlength' => 2, 'style' => 'width: 60px;'],
            ])
            ->add('numTts', IntegerType::class, [
                'label' => 'TTS',
                'required' => false,
                'attr' => ['min' => 0, 'max' => 99, 'maxlength' => 2, 'style' => 'width: 60px;'],
            ])
            ->add('numDue', IntegerType::class, [
                'label' => 'DUE',
                'required' => false,
                'attr' => ['min' => 0, 'max' => 99, 'maxlength' => 2, 'style' => 'width: 60px;'],
            ])
            ->add('numDoctors', IntegerType::class, [
                'label' => 'Médico',
                'required' => false,
                'attr' => ['min' => 0, 'max' => 99, 'maxlength' => 2, 'style' => 'width: 60px;'],
            ])
            ->add('hasFieldHospital', CheckboxType::class, [
                'label' => 'Hospital de Campaña',
                'required' => false,
            ])
            ->add('tasks', TextareaType::class, [
                'label' => 'Tareas',
                'required' => false,
                'attr' => [
                    'rows' => 2,
                    'class' => 'form-control',
                ],
            ])
            ->add('hasProvisions', CheckboxType::class, [
                'label' => 'Avituallamiento',
                'required' => false,
            ])
            ->add('vehicles', EntityType::class, [
                'class' => Vehicle::class,
                'choice_label' => function(Vehicle $vehicle) {
                    return sprintf('%s (%s)', $vehicle->getAlias() ?: $vehicle->getModel(), $vehicle->getLicensePlate());
                },
                'multiple' => true,
                'label' => 'Vehículos',
                'required' => false,
            ])
            ->add('serviceMaterials', CollectionType::class, [
                'entry_type' => ServiceMaterialFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])
            ->add('whatsappMessage', TextareaType::class, [
                'label' => 'Mensaje de WhatsApp',
                'required' => false,
                'attr' => ['rows' => 8, 'class' => 'whatsapp-message-textarea'],
            ]);

    }

    /**
     * Configures the options for this form type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}