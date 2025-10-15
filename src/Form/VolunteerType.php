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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class VolunteerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $licenseChoices = [
            'A1' => 'A1', 'A' => 'A', 'B' => 'B', 'C1' => 'C1',
            'C' => 'C', 'D1' => 'D1', 'D' => 'D', 'EC' => 'EC',
        ];

        $builder
            ->add('name', TextType::class, ['label' => 'Nombre', 'required' => true])
            ->add('lastName', TextType::class, ['label' => 'Apellidos', 'required' => true])
            ->add('dni', TextType::class, ['label' => 'DIN/NIE', 'required' => true])
            ->add('phone', TextType::class, ['label' => 'Teléfono', 'required' => true])
            ->add('user', UserType::class, ['label' => false])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Fecha de Nacimiento',
                'widget' => 'single_text',
                'html5' => true,
                'required' => true,
                'attr' => ['max' => (new \DateTime())->modify('-16 years')->format('Y-m-d')],
            ])
            ->add('profession', TextType::class, ['label' => 'Profesión', 'required' => false])
            ->add('indicativo', TextType::class, [
                'label' => 'Indicativo',
                'required' => false,
                 'attr' => ['list' => 'indicativos-list', 'placeholder' => 'Ej: L30, V45...', 'autocomplete' => 'off']
            ])
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
            ->add('address', TextType::class, ['label' => 'Dirección', 'required' => true, 'attr' => ['placeholder' => 'Ej: Gran Vía, 10, 3º A']])
            ->add('city', ChoiceType::class, [
                'label' => 'Población',
                'required' => true,
                'placeholder' => 'Población',
                'choices' => [], // Will be loaded by JS
            ])
            ->add('province', ChoiceType::class, [
                'label' => 'Provincia',
                'required' => true,
                'choices' => ['A Coruña' => 'A Coruña', 'Lugo' => 'Lugo', 'Ourense' => 'Ourense', 'Pontevedra' => 'Pontevedra'],
                'placeholder' => 'Selecciona una provincia',
            ])
            ->add('postalCode', TextType::class, ['label' => 'Código Postal', 'required' => true])
            ->add('contactPerson1', TextType::class, ['label' => 'Nombre de Contacto de Emergencia', 'required' => true])
            ->add('contactPhone1', TextType::class, ['label' => 'Teléfono de Emergencia', 'required' => true])
            ->add('contactPerson2', TextType::class, ['label' => 'Nombre de Contacto de Emergencia 2', 'required' => false])
            ->add('contactPhone2', TextType::class, ['label' => 'Teléfono de Emergencia 2', 'required' => false])
            ->add('foodAllergies', TextareaType::class, ['label' => 'Alergias Alimentarias', 'required' => false, 'attr' => ['placeholder' => 'Indica si tiene alguna alergia alimentaria']])
            ->add('otherAllergies', TextareaType::class, ['label' => 'Otras Alergias (medicamentos, etc.)', 'required' => false, 'attr' => ['placeholder' => 'Indica cualquier otra alergia relevante']])
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
                'choices' => $licenseChoices,
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'mapped' => false, // This field will be manually handled
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
            ->add('otherQualifications', TextareaType::class, ['label' => 'Otras Cualificaciones', 'required' => false, 'attr' => ['placeholder' => 'Otros títulos, cursos, etc.']])
            ->add('employmentStatus', ChoiceType::class, [
                'label' => 'Situación Laboral',
                'choices' => [
                    'Estudiante' => 'estudiante',
                    'Empleado a tiempo completo' => 'empleado_completo',
                    'Empleado a tiempo parcial' => 'empleado_parcial',
                    'Autónomo' => 'autonomo',
                    'Desempleado' => 'desempleado',
                    'Jubilado' => 'jubilado',
                    'Otro' => 'otro',
                ],
                'required' => true,
            ])
            ->add('habilitadoConducir', CheckboxType::class, ['label'    => 'Habilitado para conducir vehículos de la asociación', 'required' => false])
            ->add('languages', TextType::class, ['label' => 'Idiomas', 'required' => false, 'attr' => ['placeholder' => 'Ej: Inglés, Francés...']])
            ->add('motivation', TextareaType::class, ['label' => 'Motivación para ser voluntario', 'required' => true])
            ->add('howKnown', TextType::class, ['label' => '¿Cómo nos has conocido?', 'required' => true])
            ->add('hasVolunteeredBefore', ChoiceType::class, [
                'label' => '¿Tienes experiencia previa como voluntario?',
                'choices' => ['Sí' => true, 'No' => false],
                'expanded' => true,
                'required' => true,
            ])
            ->add('previousVolunteeringInstitutions', TextareaType::class, ['label' => 'Si es así, ¿dónde?', 'required' => false, 'attr' => ['placeholder' => 'Ej: Cruz Roja, Cáritas...']])
            ->add('profilePicture', FileType::class, [
                'label' => 'Foto de Perfil',
                'mapped' => false,
                'required' => false,
                'constraints' => [new File(['maxSize' => '1024k', 'mimeTypes' => ['image/jpeg', 'image/png']])],
            ]);

        // Add a date field for each license type, but not mapped to the entity
        foreach ($licenseChoices as $label => $value) {
            $builder->add('expiry_date_' . $value, DateType::class, [
                'label' => 'Caducidad ' . $label,
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
                'mapped' => false,
            ]);
        }

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if (isset($data['hasVolunteeredBefore']) && $data['hasVolunteeredBefore'] === '1') {
                $form->add('previousVolunteeringInstitutions', TextareaType::class, [
                    'label' => 'Si es así, ¿dónde?',
                    'required' => true,
                    'constraints' => [new NotBlank(['message' => 'Este campo es obligatorio si tienes experiencia previa.'])],
                    'attr' => ['placeholder' => 'Ej: Cruz Roja, Cáritas...'],
                ]);
            }

            // On pre-submit, process the dynamic fields into the main drivingLicenses field
            $licensesData = [];
            if (!empty($data['drivingLicenses'])) {
                foreach ($data['drivingLicenses'] as $licenseType) {
                    $dateFieldName = 'expiry_date_' . $licenseType;
                    $licensesData[] = [
                        'type' => $licenseType,
                        'expiryDate' => $data[$dateFieldName] ?? null,
                    ];
                }
            }
            // This property does not exist on the form, so we set it on the underlying data
            $volunteer = $form->getData();
            $volunteer->setDrivingLicenses($licensesData);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Volunteer::class,
            'is_edit' => false,
        ]);
    }
}