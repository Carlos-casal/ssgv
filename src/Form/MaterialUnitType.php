<?php

namespace App\Form;

use App\Entity\MaterialUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterialUnitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('serialNumber', TextType::class, [
                'label' => 'Nº de Serie / ID',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('isInMaintenance', CheckboxType::class, [
                'label' => 'Modo Mantenimiento',
                'attr' => ['class' => 'form-check-input'],
                'required' => false
            ])
            ->add('pttStatus', TextType::class, [
                'label' => 'Estado PTT',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('coverStatus', TextType::class, [
                'label' => 'Estado Funda',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('batteryStatus', TextType::class, [
                'label' => 'Estado Batería',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MaterialUnit::class,
        ]);
    }
}
