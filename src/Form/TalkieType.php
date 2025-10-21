<?php

namespace App\Form;

use App\Entity\Talkie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TalkieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', TextType::class, ['label' => 'Marca'])
            ->add('model', TextType::class, ['label' => 'Modelo'])
            ->add('serialNumber', TextType::class, ['label' => 'Número de Serie'])
            ->add('type', ChoiceType::class, [
                'label' => 'Tipo',
                'choices' => [
                    'Digital' => 'digital',
                    'Analógico' => 'analogico',
                    'Base' => 'base',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Estado',
                'choices' => [
                    'Operativo' => 'operativo',
                    'En reparación' => 'reparacion',
                    'Baja' => 'baja',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Talkie::class,
        ]);
    }
}
