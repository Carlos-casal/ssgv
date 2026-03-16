<?php

namespace App\Form;

use App\Entity\MaterialBatch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class MaterialBatchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('batchNumber', TextType::class, [
                'label' => 'LOTE',
                'attr' => ['class' => 'form-control']
            ])
            ->add('expirationDate', DateType::class, [
                'label' => 'Fecha de Caducidad',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('supplier', TextType::class, [
                'label' => 'Proveedor',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('unitsPerPackage', TextType::class, [
                'label' => 'Uds/Envase',
                'attr' => ['class' => 'form-control']
            ])
            ->add('numPackages', TextType::class, [
                'label' => 'Nº Envases',
                'attr' => ['class' => 'form-control']
            ])
            ->add('totalPrice', TextType::class, [
                'label' => 'Precio Compra (IVA inc.)',
                'attr' => ['class' => 'form-control']
            ])
            ->add('marginPercentage', TextType::class, [
                'label' => '% Margen',
                'attr' => ['class' => 'form-control']
            ])
            ->add('iva', TextType::class, [
                'label' => 'IVA (%)',
                'data' => 21,
                'attr' => ['class' => 'form-control']
            ])
        ;

        $spanishNumericTransformer = new CallbackTransformer(
            function ($value) {
                if ($value === null || $value === '') return '';
                return number_format((float)$value, 2, ',', '.');
            },
            function ($value) {
                if ($value === null || $value === '') return null;
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
                return (string) $value;
            }
        );

        $spanishIntegerTransformer = new CallbackTransformer(
            function ($value) {
                if ($value === null || $value === '') return '';
                return number_format((int)$value, 0, ',', '.');
            },
            function ($value) {
                if ($value === null || $value === '') return null;
                $value = str_replace('.', '', $value);
                return (int) preg_replace('/[^0-9]/', '', $value);
            }
        );

        $builder->get('unitsPerPackage')->addModelTransformer($spanishIntegerTransformer);
        $builder->get('numPackages')->addModelTransformer($spanishIntegerTransformer);
        $builder->get('iva')->addModelTransformer($spanishIntegerTransformer);

        $builder->get('totalPrice')->addModelTransformer($spanishNumericTransformer);
        $builder->get('marginPercentage')->addModelTransformer($spanishNumericTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MaterialBatch::class,
        ]);
    }
}
