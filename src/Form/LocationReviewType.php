<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\LocationReview;
use App\Entity\Volunteer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
                'label' => 'Ubicación a Revisar',
                'attr' => ['class' => 'form-control']
            ])
            ->add('reviewDate', DateType::class, [
                'label' => 'Fecha de Revisión',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('responsible', EntityType::class, [
                'class' => Volunteer::class,
                'choice_label' => 'name',
                'label' => 'Voluntario Responsable',
                'attr' => ['class' => 'form-control']
            ])
            ->add('result', ChoiceType::class, [
                'label' => 'Resultado de la Auditoría',
                'choices' => [
                    'Conforme (Todo OK)' => 'CONFORME',
                    'Faltan Artículos / Caducados' => 'FALTAN_ARTICULOS',
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('nextReviewDate', DateType::class, [
                'label' => 'Próxima Revisión Programada',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'help' => 'Por defecto se programa en 30 días'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LocationReview::class,
        ]);
    }
}
