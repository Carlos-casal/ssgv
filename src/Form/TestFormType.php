<?php

namespace App\Form;

use App\Form\Type\FloatingLabelEmailType;
use App\Form\Type\FloatingLabelTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dni', FloatingLabelTextType::class, [
                'label' => 'DNI/NIE',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor, introduce tu DNI.']),
                ],
            ])
            ->add('name', FloatingLabelTextType::class, [
                'label' => 'Nombre',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor, introduce tu nombre.']),
                ],
            ])
            ->add('lastName', FloatingLabelTextType::class, [
                'label' => 'Apellidos',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor, introduce tu apellido.']),
                ],
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Fecha de Nacimiento',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'data' => (new \DateTime())->modify('-16 years'),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, selecciona tu fecha de nacimiento.',
                    ]),
                    new LessThanOrEqual([
                        'value' => '-16 years',
                        'message' => 'Debes tener al menos 16 años.',
                    ]),
                ],
                'attr' => [
                    'class' => 'js-datepicker',
                    'placeholder' => 'DD/MM/AAAA'
                ]
            ])
            ->add('phone', FloatingLabelTextType::class, [
                'label' => 'Teléfono',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduce tu número de teléfono.',
                    ]),
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
