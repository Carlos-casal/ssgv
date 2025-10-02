<?php

namespace App\Form;

use App\Entity\Vehicle;
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
            ])
            ->add('model', TextType::class, [
                'label' => 'Modelo',
                'required' => true,
            ])
            ->add('licensePlate', TextType::class, [
                'label' => 'Matrícula',
                'required' => true,
            ])
            ->add('photo', FileType::class, [
                'label' => 'Foto (archivo de imagen)',
                'mapped' => false,
                'required' => false,
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
            ])
            ->add('registrationDate', DateType::class, [
                'label' => 'Fecha de Matriculación',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('fuelType', TextType::class, [
                'label' => 'Tipo de Combustible',
                'required' => false,
            ])
            ->add('type', TextType::class, [
                'label' => 'Tipo (Camión, Dron, ...)',
                'required' => false,
            ])
            ->add('nextRevisionDate', DateType::class, [
                'label' => 'Próxima ITV/Revisión',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('insuranceDueDate', DateType::class, [
                'label' => 'Vencimiento del Seguro',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('cabinType', TextType::class, [
                'label' => 'Tipo de Cabina',
                'required' => false,
            ])
            ->add('resources', TextType::class, [
                'label' => 'Recursos (V.I.R., Logística, etc)',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}