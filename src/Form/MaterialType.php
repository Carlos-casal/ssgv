<?php

namespace App\Form;

use App\Entity\Material;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class MaterialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'NOMBRE COMERCIAL**',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ej: Paracetamol 500mg'
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
                'label' => 'Tipo de Tallaje',
                'choices' => [
                    'No aplica' => '',
                    'Tallaje Textil (XS-3XL)' => Material::SIZING_LETTER,
                    'Tallaje Ropa (32-60)' => Material::SIZING_NUMBER_CLOTHING,
                    'Tallaje Calzado (35-48)' => Material::SIZING_NUMBER_SHOES,
                ],
                'attr' => [
                    'class' => 'form-control',
                    'data-material-dynamic-form-target' => 'sizingType',
                    'data-action' => 'change->material-dynamic-form#handleSizingChange'
                ],
                'required' => false
            ])
            ->add('nature', ChoiceType::class, [
                'label' => 'NATURALEZA**',
                'choices' => [
                    'Consumible (Fungible)' => Material::NATURE_CONSUMABLE,
                    'Equipo Técnico (No Fungible)' => Material::NATURE_TECHNICAL
                ],
                'attr' => [
                    'class' => 'form-control',
                    'data-material-dynamic-form-target' => 'natureSelect',
                    'data-action' => 'change->material-dynamic-form#toggleTechnicalBlock'
                ]
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'STOCK TOTAL',
                'attr' => [
                    'class' => 'form-control',
                    'data-material-dynamic-form-target' => 'stock',
                    'data-action' => 'input->material-dynamic-form#handleStockChange'
                ]
            ])
            ->add('safetyStock', IntegerType::class, [
                'label' => 'STOCK MÍNIMO',
                'attr' => ['class' => 'form-control']
            ])
            ->add('subFamily', ChoiceType::class, [
                'label' => 'SUBFAMILIA',
                'required' => false,
                'choices' => [
                    'Analgésicos' => 'Analgésicos',
                    'Curas' => 'Curas',
                    'Inmovilización' => 'Inmovilización',
                    'Vía Aérea' => 'ViaAerea',
                    'Diagnóstico' => 'Diagnostico',
                    'Sueroterapia' => 'Sueroterapia',
                    'Medicación' => 'Medicacion',
                    'Material de Entrenamiento' => 'Entrenamiento'
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('barcode', TextType::class, [
                'label' => 'CÓDIGO DE BARRAS / QR',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'EAN-13, QR o código interno'
                ],
                'help' => 'Escanea o introduce el código de barras del artículo'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'DESCRIPCIÓN DETALLADA',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Describe las características, especificaciones técnicas, o detalles relevantes del material...'
                ]
            ])
            ->add('batchNumber', TextType::class, [
                'label' => 'LOTE',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nº de lote para fungibles'
                ]
            ])
            ->add('packagingFormat', TextType::class, [
                'label' => 'FORMATO (EJ: CAJA, LITRO)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ej: Caja, Blister, Rollo...'
                ]
            ])
            ->add('unitsPerPackage', IntegerType::class, [
                'label' => 'UDS/ENVASE',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'data-material-dynamic-form-target' => 'unitsPerPackageInput',
                    'data-action' => 'input->material-dynamic-form#calculateStockSanitario change->material-dynamic-form#calculateStockSanitario'
                ]
            ])
            ->add('numPackages', IntegerType::class, [
                'label' => 'Nº ENVASES',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'data-material-dynamic-form-target' => 'numPackagesInput',
                    'data-action' => 'input->material-dynamic-form#calculateStockSanitario change->material-dynamic-form#calculateStockSanitario'
                ]
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
                'label' => 'Precio/Ud',
                'currency' => 'EUR',
                'attr' => ['class' => 'form-control']
            ])
            ->add('totalPrice', MoneyType::class, [
                'label' => 'Coste Total de la Compra',
                'mapped' => false,
                'required' => false,
                'currency' => 'EUR',
                'attr' => ['class' => 'form-control']
            ])
            ->add('discountPercentage', NumberType::class, [
                'label' => '% DTO',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ej: 10',
                    'data-material-dynamic-form-target' => 'discountPercentageInput',
                    'data-action' => 'input->material-dynamic-form#calculateUnitPrice change->material-dynamic-form#calculateUnitPrice'
                ]
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
            ->add('brandModel', TextType::class, [
                'label' => 'Marca y Modelo',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ej: Motorola MTP3550']
            ])
            ->add('deviceType', ChoiceType::class, [
                'label' => 'Tipo de Equipo',
                'required' => false,
                'choices' => [
                    'Portátil' => 'PORTATIL',
                    'Emisora Móvil' => 'MOVIL',
                    'Base Fija' => 'FIJA',
                    'Smartphone' => 'SMARTPHONE',
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('serialNumber', TextType::class, [
                'label' => 'NÚMERO DE SERIE (S/N)',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Identificador único']
            ])
            ->add('operationalStatus', ChoiceType::class, [
                'label' => 'ESTADO OPERATIVO',
                'required' => false,
                'choices' => [
                    'Operativo' => 'OPERATIVO',
                    'Averiado' => 'AVERIADO',
                    'En Reparación' => 'REPARACION',
                    'Baja' => 'BAJA'
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('batteryStatus', ChoiceType::class, [
                'label' => 'BATERÍA',
                'required' => false,
                'choices' => [
                    'N/A' => 'N/A',
                    'Óptima (100%)' => 'OPTIMA',
                    'Buena' => 'BUENA',
                    'Requiere Cambio' => 'CAMBIO'
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('purchaseDate', DateType::class, [
                'label' => 'Fecha de Compra',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('warrantyEndDate', DateType::class, [
                'label' => 'Fin de Garantía',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('hasCharger', CheckboxType::class, [
                'label' => 'Cargador',
                'required' => false,
                'attr' => ['class' => 'form-check-input', 'data-action' => 'change->material-dynamic-form#handleMaintenanceSync']
            ])
            ->add('hasClip', CheckboxType::class, [
                'label' => 'Pinza',
                'required' => false,
                'attr' => ['class' => 'form-check-input', 'data-action' => 'change->material-dynamic-form#handleMaintenanceSync']
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
