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
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class VolunteerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isCleanLayout = $options['is_clean_layout'];

        $builder
            ->add('dni', TextType::class, ['label' => 'DIN/NIE', 'required' => true])
            ->add('name', TextType::class, ['label' => 'Nombre', 'required' => true])
            ->add('lastName', TextType::class, ['label' => 'Apellidos', 'required' => true])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Fecha de Nacimiento', 'widget' => 'single_text', 'html5' => true, 'required' => true,
                'attr' => ['max' => (new \DateTime())->modify('-16 years')->format('Y-m-d')],
            ])
            ->add('phone', TextType::class, ['label' => 'Teléfono', 'required' => true])
            ->add('profession', TextType::class, ['label' => 'Profesión', 'required' => false])
            ->add('user', UserType::class, ['label' => false, 'is_public_registration' => $isCleanLayout])
            ->add('streetType', ChoiceType::class, [
                'label' => 'Tipo de Vía', 'required' => true,
                'choices' => [ 'Calle' => 'Calle', 'Avenida' => 'Avenida', 'Plaza' => 'Plaza', 'Paseo' => 'Paseo', 'Ronda' => 'Ronda', 'Vía' => 'Via', 'Carretera' => 'Carretera', 'Camino' => 'Camino', 'Bulevar' => 'Bulevar', 'Glorieta' => 'Glorieta', 'Urbanización' => 'Urbanización', 'Otro' => 'Otro' ],
                'placeholder' => 'Selecciona un tipo',
            ])
            ->add('address', TextType::class, ['label' => 'Dirección', 'required' => true, 'attr' => ['placeholder' => 'Ej: Gran Vía, 10, 3º A']])
            ->add('postalCode', TextType::class, ['label' => 'Código Postal', 'required' => true])
            ->add('province', ChoiceType::class, [
                'label' => 'Provincia', 'required' => true,
                'choices' => ['A Coruña' => 'A Coruña', 'Lugo' => 'Lugo', 'Ourense' => 'Ourense', 'Pontevedra' => 'Pontevedra'],
                'placeholder' => 'Selecciona una provincia',
            ])
            ->add('city', ChoiceType::class, ['label' => 'Población', 'required' => true, 'placeholder' => 'Población', 'choices' => []])
            ->add('profilePicture', FileType::class, [
                'label' => 'Foto de Perfil', 'mapped' => false, 'required' => false,
                'constraints' => [new File(['maxSize' => '1024k', 'mimeTypes' => ['image/jpeg', 'image/png']])],
            ])
            ->add('foodAllergies', TextareaType::class, ['label' => 'Alergias Alimentarias', 'required' => false, 'attr' => ['rows' => 4, 'placeholder' => 'Indica si tiene alguna alergia alimentaria']])
            ->add('otherAllergies', TextareaType::class, ['label' => 'Otras Alergias (medicamentos, etc.)', 'required' => false, 'attr' => ['rows' => 4, 'placeholder' => 'Indica cualquier otra alergia relevante']])
            ->add('contactPerson1', TextType::class, ['label' => 'Nombre Contacto Emergencia 1', 'required' => true])
            ->add('contactPhone1', TextType::class, ['label' => 'Teléfono Contacto Emergencia 1', 'required' => true])
            ->add('contactPerson2', TextType::class, ['label' => 'Nombre Contacto Emergencia 2', 'required' => false])
            ->add('contactPhone2', TextType::class, ['label' => 'Teléfono Contacto Emergencia 2', 'required' => false])
            ->add('drivingLicenses', ChoiceType::class, [
                'label' => 'Permisos de Conducción', 'choices' => [ 'A1' => 'A1', 'A' => 'A', 'B' => 'B', 'C1' => 'C1', 'C' => 'C', 'D1' => 'D1', 'D' => 'D', 'EC' => 'EC' ],
                'multiple' => true, 'expanded' => true, 'required' => false,
            ])
            ->add('drivingLicenseExpiryDate', DateType::class, ['label' => 'Fecha de Caducidad (Permiso Conducción)', 'widget' => 'single_text', 'html5' => true, 'required' => false])
            ->add('navigationLicenses', ChoiceType::class, [
                'label' => 'Permisos de Navegación', 'choices' => [ 'Licencia de Navegación (LN)' => 'LN', 'PNB' => 'PNB', 'PER' => 'PER', 'Patrón de Yate (PY)' => 'PY', 'Capitán de Yate (CY)' => 'CY' ],
                'multiple' => true, 'expanded' => true, 'required' => false,
            ])
            ->add('specificQualifications', ChoiceType::class, [
                'label' => 'Titulaciones Específicas', 'choices' => [ 'TES (Técnico en Emergencias Sanitarias)' => 'TES', 'TTS (Técnico en Transporte Sanitario)' => 'TTS', 'Enfermería' => 'Enfermeria', 'DUE (Diplomado Universitario en Enfermería)' => 'DUE', 'Médico' => 'Medico' ],
                'multiple' => true, 'expanded' => true, 'required' => false,
            ])
            ->add('otherQualifications', TextareaType::class, ['label' => 'Otras Cualificaciones', 'required' => false, 'attr' => ['rows' => 4, 'placeholder' => 'Otros títulos, cursos, etc.']])
            ->add('motivation', TextareaType::class, ['label' => 'Motivación para ser voluntario', 'required' => true, 'attr' => ['rows' => 4]])
            ->add('howKnown', TextType::class, ['label' => '¿Cómo nos has conocido?', 'required' => true])
            ->add('hasVolunteeredBefore', ChoiceType::class, [
                'label' => '¿Tienes experiencia previa como voluntario?', 'choices' => ['Sí' => true, 'No' => false],
                'expanded' => true, 'required' => true,
            ])
            ->add('previousVolunteeringInstitutions', TextareaType::class, ['label' => 'Si es así, ¿dónde?', 'required' => false, 'attr' => ['rows' => 4, 'placeholder' => 'Ej: Cruz Roja, Cáritas...']]);

        if (!$isCleanLayout) {
            $builder->add('indicativo', TextType::class, [
                'label' => 'Indicativo', 'required' => false,
                 'attr' => ['list' => 'indicativos-list', 'placeholder' => 'Ej: L30, V45...', 'autocomplete' => 'off']
            ]);
        }

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if (isset($data['hasVolunteeredBefore']) && $data['hasVolunteeredBefore'] === '1') {
                $options = $form->get('previousVolunteeringInstitutions')->getConfig()->getOptions();
                $options['required'] = true;
                $options['constraints'] = [new NotBlank(['message' => 'Este campo es obligatorio si tienes experiencia previa.'])];
                $form->add('previousVolunteeringInstitutions', TextareaType::class, $options);
            }

            if (!empty($data['drivingLicenses'])) {
                $options = $form->get('drivingLicenseExpiryDate')->getConfig()->getOptions();
                $options['required'] = true;
                $options['constraints'] = [new NotBlank(['message' => 'Debe indicar la fecha de caducidad si selecciona un permiso.'])];
                $form->add('drivingLicenseExpiryDate', DateType::class, $options);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Volunteer::class,
            'is_edit' => false,
            'is_clean_layout' => false,
        ]);
    }
}