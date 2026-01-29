<?php

namespace App\Form;

use App\Entity\Material;
use App\Entity\ServiceMaterial;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceMaterialFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('material', EntityType::class, [
                'class' => Material::class,
                'choice_label' => 'name',
                'placeholder' => 'Selecciona un material',
            ])
            ->add('quantity', IntegerType::class, [
                'attr' => ['min' => 0, 'max' => 99, 'class' => 'mini-input'],
                'label' => 'Cant.',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceMaterial::class,
        ]);
    }
}
