<?php

namespace App\Form;

use App\Form\Type\FloatingLabelTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dni', FloatingLabelTextType::class, [
                'label' => 'DNI/NIE',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor, introduce tu DNI o NIE.']),
                ],
            ])
            ->add('nombre', FloatingLabelTextType::class, [
                'label' => 'Nombre',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor, introduce tu nombre.']),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'El nombre solo puede contener letras y espacios.',
                    ]),
                ],
            ])
            ->add('apellidos', FloatingLabelTextType::class, [
                'label' => 'Apellidos',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor, introduce tus apellidos.']),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Los apellidos solo pueden contener letras y espacios.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
