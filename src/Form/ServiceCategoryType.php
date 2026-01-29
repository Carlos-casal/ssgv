<?php

namespace App\Form;

use App\Entity\ServiceCategory;
use App\Entity\ServiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ServiceCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre de la categoría de servicio',
                'required' => true,
            ])
            ->add('code', TextType::class, [
                'label' => 'Código (e.g. 1.1)',
                'required' => false,
            ])
            ->add('type', EntityType::class, [
                'class' => ServiceType::class,
                'choice_label' => 'name',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceCategory::class,
            'csrf_protection' => false,
        ]);
    }
}
