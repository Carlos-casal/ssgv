<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre de la Ubicación',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ej: Almacén Central, VIR-1, Mochila Soporte Vital...']
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Tipo de Ubicación',
                'choices' => [
                    'Almacén Principal' => Location::TYPE_WAREHOUSE,
                    'Vehículo' => Location::TYPE_VEHICLE,
                    'Kit / Mochila' => Location::TYPE_KIT,
                    'Desplegado (Servicio)' => Location::TYPE_DEPLOYED,
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('vehicle', EntityType::class, [
                'class' => Vehicle::class,
                'choice_label' => 'alias',
                'label' => 'Vehículo Asociado (Opcional)',
                'required' => false,
                'placeholder' => 'Ninguno',
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
