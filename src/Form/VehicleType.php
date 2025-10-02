<?php

namespace App\Form;

use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

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
            ->add('year', IntegerType::class, [
                'label' => 'Año',
                'required' => true,
            ])
            ->add('licensePlate', TextType::class, [
                'label' => 'Matrícula',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}