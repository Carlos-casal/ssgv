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
use Symfony\Component\Form\Extension\Core\Type\FileType; // ¡AÑADE ESTA LÍNEA!
use Symfony\Component\Validator\Constraints\File; // ¡AÑADE ESTA LÍNEA!

/**
 * Form type for creating and editing Volunteer profiles.
 * This is a comprehensive form that covers personal data, contact information, qualifications, and account details.
 */
class VolunteerType extends AbstractType
{
    /**
     * Builds the form structure for the Volunteer entity.
     *
     * This method defines all the fields required for a volunteer's profile, organized into sections.
     * It includes personal details, emergency contacts, professional information, qualifications, and embeds the UserType form
     * for account management.
     *
     * @param FormBuilderInterface $builder The form builder.
     * @param array $options The options for building the form, including a custom 'is_edit' option.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // --- Datos Personales ---
            ->add('name', TextType::class, [
                'label' => 'Nombre Completo del Voluntario',
                'attr' => ['placeholder' => 'Ej: Juan'],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Apellidos',
                'attr' => ['placeholder' => 'Ej: García López'],
                'required' => false,
            ])
            ->add('dni', TextType::class, [
                'label' => 'DNI',
                'attr' => ['placeholder' => 'Ej: 12345678A'],
                'required' => false,
            ])
            ->add('indicativo', TextType::class, [
                'label' => 'Indicativo',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Selecciona o introduce un indicativo',
                    'list' => 'indicativos-list',
                ],
                'help' => 'Puedes seleccionar de la lista de sugerencias o introducir uno nuevo.',
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Fecha de Nacimiento',
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
            ])
            ->add('habilitadoConducir', CheckboxType::class, [
                'label' => 'Habilitado para conducir vehículos de la asociación',
                'required' => false,
            ])
            ->add('streetType', ChoiceType::class, [
                'label' => 'Tipo de Vía',
                'choices' => [
                    'Calle' => 'Calle',
                    'Avenida' => 'Avenida',
                    'Plaza' => 'Plaza',
                    'Paseo' => 'Paseo',
                    'Ronda' => 'Ronda',
                    'Vía' => 'Via',
                    'Carretera' => 'Carretera',
                    'Camino' => 'Camino',
                    'Bulevar' => 'Bulevar',
                    'Glorieta' => 'Glorieta',
                    'Urbanización' => 'Urbanización',
                    'Bloque' => 'Bloque',
                    'Edificio' => 'Edificio',
                    'Otro' => 'Otro',
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
            ->add('phone', TextType::class, [
                'label' => 'Teléfono de Contacto',
                'attr' => ['placeholder' => 'Ej: +34 600 123 456'],
            ])
            
            // --- Datos de Contacto de Emergencia ---
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

            // --- Datos de Salud ---
            ->add('allergies', TextareaType::class, [
                'label' => 'Alergias / Consideraciones de Salud (Opcional)',
                'attr' => ['placeholder' => 'Ej: Alergia al polen, Diabético. Especificar tipo y precauciones.'],
                'required' => false,
            ])

            // --- Datos Profesionales ---
            ->add('profession', TextType::class, [
                'label' => 'Profesión',
                'attr' => ['placeholder' => 'Ej: Médico, Ingeniero, Estudiante'],
                'required' => false,
            ])
            ->add('employmentStatus', ChoiceType::class, [
                'label' => 'Situación Laboral',
                'choices' => [
                    'Activo' => 'Activo',
                    'Desempleado' => 'Desempleado',
                    'Estudiante' => 'Estudiante',
                    'Jubilado' => 'Jubilado',
                    'Otro' => 'Otro',
                ],
                'placeholder' => 'Selecciona una opción',
                'required' => false,
            ])
            ->add('role', TextType::class, [
                'label' => 'Rol en la Agrupación',
                'required' => true,
                'attr' => ['placeholder' => 'Ej: Voluntario, Jefe de Unidad'],
            ])
            ->add('drivingLicenses', ChoiceType::class, [
                'label' => 'Permiso de Conducción',
                'choices' => [
                    'A1' => 'A1',
                    'A' => 'A',
                    'B' => 'B',
                    'C1' => 'C1',
                    'C' => 'C',
                    'D1' => 'D1',
                    'D' => 'D',
                    'EC' => 'EC',
                ],
                'multiple' => true,
                'expanded' => true, // Muestra como checkboxes
                'required' => false,
            ])
            ->add('drivingLicenseExpiryDate', DateType::class, [
                'label' => 'Fecha de Caducidad del Permiso de Conducción',
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
            ])

            // --- Otros Datos e Intereses ---
            ->add('languages', TextareaType::class, [
                'label' => 'Idiomas (indicar cada idioma con el nivel que posee: bajo, medio o alto)',
                'attr' => ['placeholder' => 'Ej: Inglés: alto, Francés: medio, Alemán: bajo'],
                'required' => false,
            ])
            ->add('motivation', TextareaType::class, [
                'label' => 'Motivos por los que quiere ser voluntario',
                'attr' => ['placeholder' => 'Describe tus motivaciones para unirte al voluntariado.'],
                'required' => false,
            ])
            ->add('howKnown', TextType::class, [
                'label' => '¿Cómo nos ha conocido?',
                'attr' => ['placeholder' => 'Ej: Redes sociales, Amigo, Evento'],
                'required' => false,
            ])
            ->add('hasVolunteeredBefore', ChoiceType::class, [
                'label' => '¿Ha realizado funciones de voluntariado con anterioridad?',
                'choices' => [
                    'Sí' => true,
                    'No' => false,
                ],
                'expanded' => true, // Muestra como radio buttons
                'multiple' => false,
                'required' => false,
            ])
            ->add('previousVolunteeringInstitutions', TextareaType::class, [
                'label' => 'En caso afirmativo, indique la institución o instituciones donde ha realizado funciones de voluntariado',
                'attr' => ['placeholder' => 'Ej: Cruz Roja (2018-2019), Cáritas (2020)'],
                'required' => false,
            ])
            ->add('otherQualifications', TextareaType::class, [
                'label' => 'Otros Títulos, Lugar y Año (distintos a los anteriores)',
                'attr' => ['placeholder' => 'Ej: Curso de rescate (Madrid, 2020), Certificado de buceo (Canarias, 2018)'],
                'required' => false,
            ])
            ->add('navigationLicenses', ChoiceType::class, [
                'label' => 'Permisos de Navegación',
                'choices' => [
                    'Licencia de Navegación (LN)' => 'LN',
                    'Patrón de Navegación Básica (PNB)' => 'PNB',
                    'Patrón de Embarcaciones de Recreo (PER)' => 'PER',
                    'Patrón de Yate (PY)' => 'PY',
                    'Capitán de Yate (CY)' => 'CY',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])

            
            // --- Datos de Acceso (del UserType anidado) ---
            ->add('user', UserType::class, [
                'label' => false, // La etiqueta general se puede poner en la plantilla si es necesario
                'by_reference' => false,
                // Pasar la opción 'is_edit' al UserType
                'is_edit' => $options['is_edit'],
            ])

            ->add('profilePicture', FileType::class, [
                'label' => 'Foto de Perfil (JPG, PNG)',
                'mapped' => false, // Importante: se maneja manualmente en el controlador
                'required' => false, // No es obligatorio cambiar la foto al editar
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Por favor, sube una imagen JPG o PNG válida.',
                        'maxSizeMessage' => 'La imagen es demasiado grande ({{ size }} {{ suffix }}). El tamaño máximo permitido es {{ limit }} {{ suffix }}.',
                    ])
                ],
                'attr' => [
                    'accept' => 'image/jpeg, image/png', // Para el diálogo del navegador
                ],
                // El 'help' se puede ajustar o manejar directamente en la plantilla para mostrar la imagen actual
                // 'help' => ($options['data'] && $options['data']->getProfilePicture()) ? 'Reemplazar foto actual.' : 'Subir nueva foto.',
            ])
        ;
    }

    /**
     * Configures the options for this form type.
     *
     * It defines a custom option `is_edit` to differentiate between creation and editing contexts,
     * which is passed down to the embedded UserType form.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Volunteer::class,
            'is_edit' => false,
        ]);
    }
}