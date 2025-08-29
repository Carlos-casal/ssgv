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
                'label' => false, // La etiqueta se pondrá en la cabecera de la tabla
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-input w-full',
                ],
            ])
            ->add('checkOutTime', DateTimeType::class, [
                'label' => false, // La etiqueta se pondrá en la cabecera de la tabla
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
                 'attr' => [
                    'class' => 'form-input w-full',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AssistanceConfirmation::class,
        ]);
    }
}
