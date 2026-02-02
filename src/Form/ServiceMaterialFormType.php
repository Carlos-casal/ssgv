<?php

namespace App\Form;

use App\Entity\Material;
use App\Entity\MaterialUnit;
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
                'choice_attr' => function(Material $material) {
                    return [
                        'data-category' => $material->getCategory(),
                        'data-nature' => $material->getNature()
                    ];
                },
                'placeholder' => 'Selecciona un material',
                'attr' => ['class' => 'form-select material-selector']
            ])
            ->add('materialUnit', EntityType::class, [
                'class' => MaterialUnit::class,
                'choice_label' => function(MaterialUnit $unit) {
                    return $unit->getSerialNumber() ?: 'S/N ' . $unit->getId();
                },
                'placeholder' => 'Unidad auto-asignada',
                'required' => false,
                'attr' => ['class' => 'form-select unit-selector'],
            ])
            ->add('quantity', IntegerType::class, [
                'attr' => ['min' => 0, 'max' => 999, 'class' => 'mini-input quantity-input'],
                'label' => 'Cant.',
                'empty_data' => '1',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceMaterial::class,
        ]);
    }
}
