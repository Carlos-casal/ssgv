<?php

namespace App\Form;

use App\Form\Type\FloatingLabelEmailType;
use App\Form\Type\FloatingLabelTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dni', FloatingLabelTextType::class, [
                'label' => 'DNI',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor, introduce tu DNI.']),
                ],
            ])
            ->add('nombre', FloatingLabelTextType::class, [
                'label' => 'Nombre',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor, introduce tu nombre.']),
                ],
            ])
            ->add('apellido', FloatingLabelTextType::class, [
                'label' => 'Apellido',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor, introduce tu apellido.']),
                ],
            ])
            ->add('email', FloatingLabelEmailType::class, [
                'label' => 'Correo Electrónico',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor, introduce tu correo electrónico.']),
                ],
            ]);
    }
}
