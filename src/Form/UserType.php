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

/**
 * Form type for creating and editing User entities.
 * This form is typically embedded within other forms, like VolunteerType.
 */
class UserType extends AbstractType
{
    /**
     * Builds the form structure for the User entity.
     *
     * @param FormBuilderInterface $builder The form builder.
     * @param array $options The options for building the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Correo Electrónico',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduce un correo electrónico.',
                    ]),
                ],
            ])
            ->add('password', \Symfony\Component\Form\Extension\Core\Type\RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options'  => [
                    'label' => 'Contraseña',
                    'attr' => [
                        'placeholder' => $options['is_edit'] ? 'Dejar en blanco para no cambiar' : 'Introduce una contraseña',
                    ],
                ],
                'second_options' => [
                    'label' => 'Repetir Contraseña',
                    'attr' => [
                        'placeholder' => 'Vuelve a introducir la contraseña',
                    ],
                ],
                'invalid_message' => 'Las contraseñas deben coincidir.',
                'required' => !$options['is_edit'],
                'constraints' => $this->getPasswordConstraints($options['is_edit']),
            ]);
    }

    /**
     * Gets the appropriate validation constraints for the password field based on the context.
     *
     * @param bool $isEdit Whether the form is in edit mode.
     * @return array An array of validation constraints.
     */
    private function getPasswordConstraints(bool $isEdit): array
    {
        // The Length constraint always applies if a value is entered.
        // If it's an edit form and the field is empty, Length won't be validated
        // thanks to 'required' => false and the absence of NotBlank.
        $constraints = [
            new Length([
                'min' => 6,
                'minMessage' => 'Tu contraseña debe tener al menos {{ limit }} caracteres.',
                'max' => 4096,
            ]),
        ];

        if (!$isEdit) {
            // Only add NotBlank if we are creating a new user (not in edit mode)
            array_unshift($constraints, new NotBlank([
                'message' => 'Por favor, introduce una contraseña.',
            ]));
        }

        return $constraints;
    }

    /**
     * Configures the options for this form type.
     *
     * It defines a custom option `is_edit` to differentiate between creation and editing contexts,
     * which controls whether the password field is required.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
        ]);

        $resolver->setAllowedTypes('is_edit', 'bool');
    }
}