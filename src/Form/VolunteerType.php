<?php

namespace App\Form;

use App\Entity\Volunteer;
use App\Form\Type\FloatingLabelDateType;
use App\Form\Type\FloatingLabelEmailType;
use App\Form\Type\FloatingLabelTextareaType;
use App\Form\Type\FloatingLabelTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\FileType; // ¡AÑADE ESTA LÍNEA!
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

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
            ->add('name', FloatingLabelTextType::class, [
                'label' => 'Nombre',
                'required' => true,
            ])
            ->add('lastName', FloatingLabelTextType::class, [
                'label' => 'Apellidos',
                'required' => true,
            ])
            ->add('dni', FloatingLabelTextType::class, [
                'label' => 'DIN/NIE',
                'required' => true,
            ])
            ->add('phone', FloatingLabelTextType::class, [
                'label' => 'Teléfono',
                'required' => true,
            ])
            ->add('email', FloatingLabelEmailType::class, [
                'label' => 'Correo Electrónico',
                'mapped' => false, // No se mapea directamente a la entidad Volunteer, sino a User
                'required' => true,
            ])
            ->add('dateOfBirth', FloatingLabelDateType::class, [
                'label' => 'Fecha de Nacimiento',
                'widget' => 'single_text',
                'html5' => true,
                'required' => true,
            ])

            // --- Dirección ---
            ->add('streetType', ChoiceType::class, [
                'label' => 'Tipo de Vía',
                'required' => true,
                'choices' => [
                    'Calle' => 'Calle', 'Avenida' => 'Avenida', 'Plaza' => 'Plaza', 'Paseo' => 'Paseo',
                    'Ronda' => 'Ronda', 'Vía' => 'Via', 'Carretera' => 'Carretera', 'Camino' => 'Camino',
                    'Bulevar' => 'Bulevar', 'Glorieta' => 'Glorieta', 'Urbanización' => 'Urbanización',
                    'Otro' => 'Otro',
                ],
                'placeholder' => 'Selecciona un tipo',
            ])
            ->add('address', FloatingLabelTextType::class, [
                'label' => 'Dirección',
                'required' => true,
                'attr' => ['placeholder' => 'Ej: Gran Vía, 10, 3º A'],
            ])
            ->add('city', FloatingLabelTextType::class, [
                'label' => 'Población',
                'required' => true,
            ])
            ->add('province', FloatingLabelTextType::class, [
                'label' => 'Provincia',
                'required' => true,
            ])
            ->add('postalCode', FloatingLabelTextType::class, [
                'label' => 'Código Postal',
                'required' => true,
            ])

            // --- Contacto de Emergencia ---
            ->add('contactPerson1', FloatingLabelTextType::class, [
                'label' => 'Nombre de Contacto de Emergencia',
                'required' => true,
            ])
            ->add('contactPhone1', FloatingLabelTextType::class, [
                'label' => 'Teléfono de Emergencia',
                'required' => true,
            ])

            // --- Datos de Salud ---
            ->add('foodAllergies', FloatingLabelTextareaType::class, [
                'label' => 'Alergias Alimentarias',
                'required' => false,
                'attr' => ['placeholder' => 'Indica si tiene alguna alergia alimentaria'],
            ])
            ->add('otherAllergies', FloatingLabelTextareaType::class, [
                'label' => 'Otras Alergias (medicamentos, etc.)',
                'required' => false,
                'attr' => ['placeholder' => 'Indica cualquier otra alergia relevante'],
            ])

            // --- Cualificaciones ---
            ->add('specificQualifications', ChoiceType::class, [
                'label' => 'Titulaciones Específicas',
                'choices' => [
                    'TES (Técnico en Emergencias Sanitarias)' => 'TES',
                    'TTS (Técnico en Transporte Sanitario)' => 'TTS',
                    'Enfermería' => 'Enfermeria',
                    'DUE (Diplomado Universitario en Enfermería)' => 'DUE',
                    'Médico' => 'Medico',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('drivingLicenses', ChoiceType::class, [
                'label' => 'Permisos de Conducción',
                'choices' => [
                    'A1' => 'A1', 'A' => 'A', 'B' => 'B', 'C1' => 'C1',
                    'C' => 'C', 'D1' => 'D1', 'D' => 'D', 'EC' => 'EC',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('drivingLicenseExpiryDate', FloatingLabelDateType::class, [
                'label' => 'Fecha de Caducidad (Carnet Conducir)',
                'widget' => 'single_text',
                'html5' => true,
                'required' => false, // Se hará obligatorio con JS y listener
            ])
            ->add('habilitadoConducir', CheckboxType::class, [
                'label' => 'Habilitado para conducir vehículos de la asociación',
                'required' => false,
            ])
            ->add('navigationLicenses', ChoiceType::class, [
                'label' => 'Permisos de Navegación',
                'choices' => [
                    'Licencia de Navegación (LN)' => 'LN', 'PNB' => 'PNB', 'PER' => 'PER',
                    'Patrón de Yate (PY)' => 'PY', 'Capitán de Yate (CY)' => 'CY',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('otherQualifications', FloatingLabelTextareaType::class, [
                'label' => 'Otras Cualificaciones',
                'required' => false,
                'attr' => ['placeholder' => 'Otros títulos, cursos, etc.'],
            ])

            // --- Motivación e Intereses ---
            ->add('motivation', FloatingLabelTextareaType::class, [
                'label' => 'Motivación para ser voluntario',
                'required' => true,
            ])
            ->add('howKnown', FloatingLabelTextType::class, [
                'label' => '¿Cómo nos has conocido?',
                'required' => true,
            ])
            ->add('hasVolunteeredBefore', ChoiceType::class, [
                'label' => '¿Tienes experiencia previa como voluntario?',
                'choices' => [
                    'Sí' => true,
                    'No' => false,
                ],
                'expanded' => true,
                'required' => true,
            ])
            ->add('previousVolunteeringInstitutions', FloatingLabelTextareaType::class, [
                'label' => 'Si es así, ¿dónde?',
                'required' => false, // Se hará obligatorio con JS y listener
                'attr' => ['placeholder' => 'Ej: Cruz Roja, Cáritas...'],
            ])

        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            // Lógica para la fecha de caducidad del carnet de conducir
            if (!empty($data['drivingLicenses'])) {
                $form->add('drivingLicenseExpiryDate', FloatingLabelDateType::class, [
                    'label' => 'Fecha de Caducidad (Carnet Conducir)',
                    'widget' => 'single_text',
                    'html5' => true,
                    'required' => true,
                    'constraints' => [
                        new NotBlank(['message' => 'La fecha de caducidad es obligatoria si seleccionas un permiso.']),
                    ],
                ]);
            }

            // Lógica para la experiencia previa de voluntariado
            if (isset($data['hasVolunteeredBefore']) && $data['hasVolunteeredBefore'] === '1') { // '1' para 'Sí'
                $form->add('previousVolunteeringInstitutions', FloatingLabelTextareaType::class, [
                    'label' => 'Si es así, ¿dónde?',
                    'required' => true,
                    'constraints' => [
                        new NotBlank(['message' => 'Este campo es obligatorio si tienes experiencia previa.']),
                    ],
                    'attr' => ['placeholder' => 'Ej: Cruz Roja, Cáritas...'],
                ]);
            }
        });
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