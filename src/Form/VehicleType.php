<?php

namespace App\Form;

use App\Entity\FuelType;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('make', TextType::class, [
                'label' => 'Marca',
                'required' => true,
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('model', TextType::class, [
                'label' => 'Modelo',
                'required' => true,
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('licensePlate', TextType::class, [
                'label' => 'Matrícula',
                'required' => true,
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('photo', FileType::class, [
                'label' => 'Foto (archivo de imagen)',
                'mapped' => false,
                'required' => false,
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Por favor, sube una imagen válida (JPEG o PNG)',
                    ])
                ],
            ])
            ->add('alias', TextType::class, [
                'label' => 'Alias o Indicativo',
                'required' => false,
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('registrationDate', DateType::class, [
                'label' => 'Fecha de Matriculación',
                'widget' => 'single_text',
                'required' => false,
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('fuelType', EntityType::class, [
                'class' => FuelType::class,
                'choice_label' => 'name',
                'label' => 'Tipo de Combustible',
                'placeholder' => 'Selecciona un tipo de combustible',
                'required' => false,
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('type', TextType::class, [
                'label' => 'Tipo (Camión, Dron, ...)',
                'required' => false,
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('nextRevisionDate', DateType::class, [
                'label' => 'Próxima ITV/Revisión',
                'widget' => 'single_text',
                'required' => false,
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('insuranceDueDate', DateType::class, [
                'label' => 'Vencimiento del Seguro',
                'widget' => 'single_text',
                'required' => false,
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('cabinType', TextType::class, [
                'label' => 'Tipo de Cabina',
                'required' => false,
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('resources', TextType::class, [
                'label' => 'Recursos (V.I.R., Logística, etc)',
                'required' => false,
                'label_attr' => ['class' => 'form-label'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}