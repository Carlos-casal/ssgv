<?php

namespace App\Form;

use App\Entity\Volunteer;
use App\Form\UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // --- Datos Personales (No editables) ---
            ->add('name', TextType::class, [
                'label' => 'Nombre Completo del Voluntario',
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

            // --- Rol (No editable) ---
            ->add('role', ChoiceType::class, [
                'label' => 'Rol en la Organización',
                'choices' => [
                    'Voluntario' => 'Voluntario',
                    'Coordinador' => 'Coordinador',
                    'Especialista' => 'Especialista',
                ],
                'disabled' => true,
            ])

            // --- Datos de Acceso (Contraseña editable) ---
            ->add('user', UserType::class, [
                'label' => 'Datos de Acceso (Email y Contraseña)',
                'by_reference' => true,
                'is_edit' => true, // Siempre es edición en el perfil
            ])

            // --- Campos Editables ---
            ->add('phone', TextType::class, [
                'label' => 'Teléfono de Contacto',
                'attr' => ['placeholder' => 'Ej: +34 600 123 456'],
            ])
            ->add('streetType', ChoiceType::class, [
                'label' => 'Tipo de Vía',
                'choices' => [
                    'Calle' => 'Calle', 'Avenida' => 'Avenida', 'Plaza' => 'Plaza', 'Paseo' => 'Paseo',
                    'Ronda' => 'Ronda', 'Vía' => 'Via', 'Carretera' => 'Carretera', 'Camino' => 'Camino',
                    'Bulevar' => 'Bulevar', 'Glorieta' => 'Glorieta', 'Urbanización' => 'Urbanización',
                    'Bloque' => 'Bloque', 'Edificio' => 'Edificio', 'Otro' => 'Otro',
                ],
                'placeholder' => 'Selecciona un tipo de vía',
                'required' => false,
            ])
            ->add('address', TextType::class, [
                'label' => 'Nombre de la Vía, Número, Piso, Puerta',
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
                'label' => 'Nombre de Persona de Contacto 1 (Emergencia)',
                'attr' => ['placeholder' => 'Ej: María Pérez'],
                'required' => false,
            ])
            ->add('contactPhone1', TextType::class, [
                'label' => 'Teléfono de Contacto 1 (Emergencia)',
                'attr' => ['placeholder' => 'Ej: +34 600 987 654'],
                'required' => false,
            ])
            ->add('contactPerson2', TextType::class, [
                'label' => 'Nombre de Persona de Contacto 2 (Opcional)',
                'attr' => ['placeholder' => 'Ej: Pedro Gómez'],
                'required' => false,
            ])
            ->add('contactPhone2', TextType::class, [
                'label' => 'Teléfono de Contacto 2 (Opcional)',
                'attr' => ['placeholder' => 'Ej: +34 600 111 222'],
                'required' => false,
            ])
            ->add('allergies', TextareaType::class, [
                'label' => 'Alergias / Consideraciones de Salud (Opcional)',
                'attr' => ['placeholder' => 'Ej: Alergia al polen, Diabético. Especificar tipo y precauciones.'],
                'required' => false,
            ])
            ->add('profession', TextType::class, [
                'label' => 'Profesión',
                'attr' => ['placeholder' => 'Ej: Médico, Ingeniero, Estudiante'],
                'required' => false,
            ])
            ->add('employmentStatus', ChoiceType::class, [
                'label' => 'Situación Laboral',
                'choices' => [
                    'Activo' => 'Activo', 'Desempleado' => 'Desempleado', 'Estudiante' => 'Estudiante',
                    'Jubilado' => 'Jubilado', 'Otro' => 'Otro',
                ],
                'placeholder' => 'Selecciona una opción',
                'required' => false,
            ])
            ->add('drivingLicenses', ChoiceType::class, [
                'label' => 'Permiso de Conducción',
                'choices' => [
                    'A1' => 'A1', 'A' => 'A', 'B' => 'B', 'C1' => 'C1', 'C' => 'C',
                    'D1' => 'D1', 'D' => 'D', 'EC' => 'EC',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('drivingLicenseExpiryDate', DateType::class, [
                'label' => 'Fecha de Caducidad del Permiso de Conducción',
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
            ])
            ->add('languages', TextareaType::class, [
                'label' => 'Idiomas (indicar nivel: bajo, medio o alto)',
                'attr' => ['placeholder' => 'Ej: Inglés: alto, Francés: medio'],
                'required' => false,
            ])
            ->add('motivation', TextareaType::class, [
                'label' => 'Motivaciones',
                'disabled' => true,
            ])
            ->add('howKnown', TextType::class, [
                'label' => '¿Cómo nos conoció?',
                'disabled' => true,
            ])
            ->add('hasVolunteeredBefore', ChoiceType::class, [
                'label' => '¿Ha sido voluntario antes?',
                'choices' => ['Sí' => true, 'No' => false],
                'expanded' => true,
                'disabled' => true,
            ])
            ->add('previousVolunteeringInstitutions', TextareaType::class, [
                'label' => 'Instituciones de voluntariado anteriores',
                'disabled' => true,
            ])
            ->add('otherQualifications', TextareaType::class, [
                'label' => 'Otras Titulaciones',
                'disabled' => true,
            ])
            ->add('navigationLicenses', ChoiceType::class, [
                'label' => 'Permisos de Navegación',
                'choices' => [
                    'Licencia de Navegación (LN)' => 'LN', 'Patrón de Navegación Básica (PNB)' => 'PNB',
                    'Patrón de Embarcaciones de Recreo (PER)' => 'PER', 'Patrón de Yate (PY)' => 'PY',
                    'Capitán de Yate (CY)' => 'CY',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('specificQualifications', ChoiceType::class, [
                'label' => 'Titulaciones Específicas',
                'choices' => [
                    'Técnico en Emergencias Sanitarias de Grado Medio' => 'TecnicoEmergencias',
                    'Certificado de profesionalidad de transporte sanitario' => 'CertificadoTransporteSanitario',
                    'Título universitario de Enfermería válido en España' => 'TituloEnfermeria',
                    'Acreditación del registro de enfermeros de transporte sanitario de la Comunidad de Madrid (DUEM)' => 'AcreditacionDUEM',
                    'Título universitario de Medicina válido en España' => 'TituloMedicina',
                    'Acreditación del registro de médicos de transporte sanitario de la Comunidad de Madrid (FUEM)' => 'AcreditacionFUEM',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('specialization', TextType::class, [
                'label' => 'Especialización',
                'disabled' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Volunteer::class,
        ]);
    }
}
