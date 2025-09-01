<?php

namespace App\Form;

use App\Entity\AssistanceConfirmation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssistanceConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('checkInTime', DateTimeType::class, [
                'label' => 'Hora de Entrada',
                'widget' => 'single_text',
                'required' => false,
                'html5' => false,
                'format' => 'HH:mm',
            ])
            ->add('checkOutTime', DateTimeType::class, [
                'label' => 'Hora de Salida',
                'widget' => 'single_text',
                'required' => false,
                'html5' => false,
                'format' => 'HH:mm',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AssistanceConfirmation::class,
        ]);
    }
}
