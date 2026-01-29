<?php

namespace App\Form;

use App\Entity\ServiceMaterial;
use App\Entity\Material;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceMaterialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('material', EntityType::class, [
                'class' => Material::class,
                'choice_label' => 'name',
            ])
            ->add('quantity', IntegerType::class, [
                'attr' => ['min' => 0, 'class' => 'mini-input'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceMaterial::class,
        ]);
    }
}
