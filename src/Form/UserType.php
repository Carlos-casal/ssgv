<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Correo Electrónico',
                'required' => true,
                'disabled' => $options['is_invitation'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduce un correo electrónico.',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Contraseña',
                'required' => !$options['is_edit'], // Solo requerido si no es edición
                'mapped' => false, // No mapear directamente, se maneja en el controlador
                'constraints' => $this->getPasswordConstraints($options['is_edit']),
                'attr' => [
                    'placeholder' => $options['is_edit'] ? 'Dejar en blanco para no cambiar' : 'Introduce una contraseña',
                ],
            ]);
    }

    private function getPasswordConstraints(bool $isEdit): array
    {
        // La restricción Length siempre se aplica si se introduce un valor.
        // Si es edición y el campo está vacío, no se validará Length gracias a 'required' => false
        // y la ausencia de NotBlank si $isEdit es true.
        $constraints = [
            new Length([
                'min' => 6,
                'minMessage' => 'Tu contraseña debe tener al menos {{ limit }} caracteres.',
                'max' => 4096,
            ]),
        ];

        if (!$isEdit) {
            // Solo añadir NotBlank si estamos creando un nuevo usuario (no en edición)
            array_unshift($constraints, new NotBlank([
                'message' => 'Por favor, introduce una contraseña.',
            ]));
        }

        return $constraints;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
            'is_invitation' => false,
        ]);

        $resolver->setAllowedTypes('is_edit', 'bool');
        $resolver->setAllowedTypes('is_invitation', 'bool');
    }
}