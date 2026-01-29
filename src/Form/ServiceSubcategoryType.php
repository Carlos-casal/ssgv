<?php

namespace App\Form;

use App\Entity\ServiceSubcategory;
use App\Entity\ServiceCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ServiceSubcategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre de la subcategoría',
                'required' => true,
            ])
            ->add('code', TextType::class, [
                'label' => 'Código (e.g. 1.1.1)',
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => ServiceCategory::class,
                'choice_label' => 'name',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceSubcategory::class,
            'csrf_protection' => false,
        ]);
    }
}
