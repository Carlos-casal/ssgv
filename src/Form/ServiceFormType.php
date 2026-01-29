<?php

namespace App\Form;

use App\Entity\Service;
use App\Entity\ServiceType;
use App\Entity\ServiceCategory;
use App\Entity\ServiceSubcategory;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Título del Servicio',
                'attr' => ['class' => 'form-control-lg fw-bold', 'placeholder' => 'Ej. Dispositivo Maratón'],
            ])
            ->add('numeration', TextType::class, [
                'label' => 'Nº de Registro',
                'required' => false,
                'attr' => ['readonly' => true, 'placeholder' => 'S-2026-XXX'],
            ])
            ->add('locality', TextType::class, [
                'label' => 'Ubicación',
                'attr' => ['placeholder' => 'Añadir ubicación...'],
            ])
            // 3-level Hierarchy (Linked selectors will be handled in JS, but defined here)
            ->add('type_selector', EntityType::class, [
                'class' => ServiceType::class,
                'choice_label' => 'name',
                'label' => 'Tipo',
                'mapped' => false,
                'placeholder' => 'Selecciona Tipo',
                'attr' => ['data-action' => 'change->service-form#updateCategories'],
            ])
            ->add('category_selector', ChoiceType::class, [
                'label' => 'Categoría',
                'mapped' => false,
                'placeholder' => 'Selecciona Categoría',
                'attr' => ['data-action' => 'change->service-form#updateSubcategories'],
                'choices' => [],
            ])
            ->add('subcategory', EntityType::class, [
                'class' => ServiceSubcategory::class,
                'choice_label' => 'name',
                'label' => 'Subcategoría',
                'placeholder' => 'Selecciona Subcategoría',
            ])
            // Operative Definition
            ->add('tasks', TextareaType::class, [
                'label' => 'Tareas',
                'attr' => ['rows' => 2],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'attr' => ['rows' => 4],
            ])
            // Chronology
            ->add('startDate', DateTimeType::class, [
                'label' => 'Inicio',
                'widget' => 'single_text',
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Fin',
                'widget' => 'single_text',
            ])
            ->add('timeAtBase', TimeType::class, [
                'label' => 'Base',
                'widget' => 'single_text',
            ])
            ->add('departureTime', TimeType::class, [
                'label' => 'Salida',
                'widget' => 'single_text',
            ])
            ->add('registrationLimitDate', DateType::class, [
                'label' => 'Límite Inscripción',
                'widget' => 'single_text',
            ])
            // Resources
            ->add('numTes', IntegerType::class, [
                'label' => 'TES',
                'attr' => ['class' => 'mini-input'],
            ])
            ->add('numTts', IntegerType::class, [
                'label' => 'TTS',
                'attr' => ['class' => 'mini-input'],
            ])
            ->add('numDue', IntegerType::class, [
                'label' => 'DUE',
                'attr' => ['class' => 'mini-input'],
            ])
            ->add('numDoctors', IntegerType::class, [
                'label' => 'Médico',
                'attr' => ['class' => 'mini-input'],
            ])
            ->add('vehicles', EntityType::class, [
                'class' => Vehicle::class,
                'choice_label' => 'alias',
                'multiple' => true,
                'expanded' => false,
                'label' => 'Vehículos',
                'attr' => ['id' => 'vehiculos_selector'],
            ])
            ->add('serviceMaterials', CollectionType::class, [
                'entry_type' => ServiceMaterialType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
