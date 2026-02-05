<?php

namespace App\Form;

use App\Entity\Material;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MaterialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre del Material',
                'attr' => ['class' => 'form-control']
            ])
            ->add('barcode', TextType::class, [
                'label' => 'Código de Barras / QR',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'EAN-13, QR, o código interno'
                ],
                'help' => 'Escanea o introduce el código de barras del artículo'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción Detallada',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Describe las características, especificaciones técnicas, o detalles relevantes del material...'
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Imagen del Material',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Por favor sube una imagen válida (JPG o PNG)',
                    ])
                ],
                'attr' => ['class' => 'form-control', 'accept' => 'image/jpeg,image/png'],
                'help' => 'Formatos permitidos: JPG, PNG. Tamaño máximo: 2MB'
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
                    'Tallaje Textil (Letras: XS, S, M...)' => Material::SIZING_LETTER,
                    'Tallaje Ropa (Números: 32-60)' => Material::SIZING_NUMBER_CLOTHING,
                    'Tallaje Calzado (Números: 35-48)' => Material::SIZING_NUMBER_SHOES,
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
            ->add('batch', TextType::class, [
                'label' => 'Lote',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nº de lote para fungibles']
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
            ->add('unitPrice', MoneyType::class, [
                'label' => 'Precio Unitario',
                'currency' => 'EUR',
                'attr' => ['class' => 'form-control']
            ])
            ->add('iva', ChoiceType::class, [
                'label' => 'IVA (%)',
                'choices' => [
                    '21%' => '21.00',
                    '10%' => '10.00',
                    '4%' => '4.00',
                    'Exento' => '0.00'
                ],
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
