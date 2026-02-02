<?php

namespace App\Form;

use App\Entity\Material;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre del Material',
                'attr' => ['class' => 'form-control']
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Categoría',
                'choices' => [
                    'Sanitario' => 'Sanitario',
                    'Comunicaciones' => 'Comunicaciones',
                    'Logística' => 'Logística',
                    'Mar' => 'Mar',
                    'Uniformidad' => 'Uniformidad',
                    'Varios' => 'Varios'
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('sizingType', ChoiceType::class, [
                'label' => 'Tipo de Tallaje (Solo Uniformidad)',
                'choices' => [
                    'No aplica' => null,
                    'Talla por Letra (XS, S, M...)' => Material::SIZING_LETTER,
                    'Talla por Número (36, 38, 40...)' => Material::SIZING_NUMBER
                ],
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('nature', ChoiceType::class, [
                'label' => 'Naturaleza',
                'choices' => [
                    'Consumible (Fungible)' => Material::NATURE_CONSUMABLE,
                    'Equipo Técnico (No Fungible)' => Material::NATURE_TECHNICAL
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'Stock Actual',
                'attr' => ['class' => 'form-control']
            ])
            ->add('safetyStock', IntegerType::class, [
                'label' => 'Stock Mínimo de Seguridad',
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Material::class,
        ]);
    }
}
