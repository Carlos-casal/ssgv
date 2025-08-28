<?php

namespace App\Form;

use App\Entity\Volunteer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Form\UserType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // --- Datos Personales (No editables) ---
            ->add('name', TextType::class, [
                'label' => 'Nombre Completo',
                'disabled' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Apellidos',
                'disabled' => true,
            ])
            ->add('dni', TextType::class, [
                'label' => 'DNI',
                'disabled' => true,
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Fecha de Nacimiento',
                'widget' => 'single_text',
                'html5' => true,
                'disabled' => true,
            ])

            // --- Rol y Especialización (No editables) ---
            ->add('role', TextType::class, [
                'label' => 'Rol en la Organización',
                'disabled' => true,
            ])
            ->add('specialization', TextType::class, [
                'label' => 'Especialización',
                'disabled' => true,
            ])

            // --- Datos de Acceso (Contraseña editable) ---
            ->add('user', UserType::class, [
                'label' => 'Datos de Acceso',
                'by_reference' => true,
                'is_edit' => true, // Forzar modo edición para el sub-formulario de usuario
            ])

            // --- Campos Editables ---
            ->add('phone', TextType::class, [
                'label' => 'Teléfono de Contacto',
                'attr' => ['placeholder' => 'Ej: +34 600 123 456'],
            ])
            ->add('streetType', ChoiceType::class, [
                'label' => 'Tipo de Vía',
                'choices' => [
                    'Calle' => 'Calle', 'Avenida' => 'Avenida', 'Plaza' => 'Plaza',
                    'Paseo' => 'Paseo', 'Ronda' => 'Ronda', 'Vía' => 'Via',
                    'Carretera' => 'Carretera', 'Camino' => 'Camino', 'Bulevar' => 'Bulevar',
                    'Glorieta' => 'Glorieta', 'Urbanización' => 'Urbanización',
                    'Bloque' => 'Bloque', 'Edificio' => 'Edificio', 'Otro' => 'Otro',
                ],
                'placeholder' => 'Selecciona un tipo de vía',
                'required' => false,
            ])
            ->add('address', TextType::class, [
                'label' => 'Dirección',
                'attr' => ['placeholder' => 'Ej: Gran Vía, 10, 3º A'],
                'required' => false,
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Código Postal',
                'attr' => ['placeholder' => 'Ej: 28001'],
                'required' => false,
            ])
            ->add('province', TextType::class, [
                'label' => 'Provincia',
                'attr' => ['placeholder' => 'Ej: Madrid'],
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'label' => 'Población',
                'attr' => ['placeholder' => 'Ej: Madrid'],
                'required' => false,
            ])
            ->add('contactPerson1', TextType::class, [
                'label' => 'Contacto de Emergencia 1',
                'attr' => ['placeholder' => 'Ej: María Pérez'],
                'required' => false,
            ])
            ->add('contactPhone1', TextType::class, [
                'label' => 'Teléfono de Emergencia 1',
                'attr' => ['placeholder' => 'Ej: +34 600 987 654'],
                'required' => false,
            ])
            ->add('allergies', TextareaType::class, [
                'label' => 'Alergias / Consideraciones de Salud',
                'attr' => ['placeholder' => 'Especificar tipo y precauciones.'],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Volunteer::class,
        ]);
    }
}
