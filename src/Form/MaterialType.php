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
use Symfony\Component\Form\CallbackTransformer;

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
            ->add('nature', ChoiceType::class, [
                'label' => 'NATURALEZA**',
                'choices' => [
                    'Consumible (Fungible)' => Material::NATURE_CONSUMABLE,
                    'Equipo Técnico (No Fungible)' => Material::NATURE_TECHNICAL
                ],
                'attr' => [
                    'class' => 'form-control',
                    'data-material-dynamic-form-target' => 'natureSelect',
                    'data-action' => 'change->material-dynamic-form#toggleTechnicalBlock change->material-dynamic-form#handleNatureChange'
                ]
            ])
            ->add('sizingType', ChoiceType::class, [
                'label' => 'TIPO DE TALLAJE',
                'required' => false,
                'choices' => [
                    'Letras (XS-3XL)' => 'LETTER',
                    'Ropa (32-60)' => 'NUMBER_CLOTHING',
                    'Calzado (35-48)' => 'NUMBER_SHOES'
                ],
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sin tallaje (Unico)'
            ])
            ->add('stock', TextType::class, [
                'label' => 'STOCK TOTAL',
                'attr' => [
                    'class' => 'form-control',
                    'data-material-dynamic-form-target' => 'stock',
                    'data-action' => 'input->material-dynamic-form#handleStockChange'
                ]
            ])
            ->add('safetyStock', TextType::class, [
                'label' => 'STOCK MÍNIMO (Nº ENVASES)',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'text'
                ]
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
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nº de lote para fungibles'
                ]
            ])
            ->add('unitsPerPackage', TextType::class, [
                'label' => 'UDS/ENVASE',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'text',
                    'data-material-dynamic-form-target' => 'unitsPerPackageInput',
                    'data-action' => 'input->material-dynamic-form#calculateStockSanitario change->material-dynamic-form#calculateStockSanitario'
                ]
            ])
            ->add('numPackages', TextType::class, [
                'label' => 'Nº ENVASES',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'text',
                    'data-material-dynamic-form-target' => 'numPackagesInput',
                    'data-action' => 'input->material-dynamic-form#calculateStockSanitario change->material-dynamic-form#calculateStockSanitario'
                ]
            ])
            ->add('expirationDate', DateType::class, [
                'label' => 'Fecha de Caducidad',
                'widget' => 'single_text',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('supplier', TextType::class, [
                'label' => 'Proveedor',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('unitPrice', TextType::class, [
                'label' => 'Precio/Ud',
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'text',
                    'readonly' => true,
                    'data-material-dynamic-form-target' => 'unitPrice'
                ]
            ])
            ->add('totalPrice', TextType::class, [
                'label' => 'Coste Total de la Compra',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'text',
                    'placeholder' => '0,00€',
                    'data-material-dynamic-form-target' => 'totalPrice',
                    'data-action' => 'input->material-dynamic-form#calculateUnitPrice'
                ]
            ])
            ->add('discountPercentage', TextType::class, [
                'label' => '% DTO',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'text',
                    'placeholder' => 'Ej: 10',
                    'data-material-dynamic-form-target' => 'discountPercentageInput',
                    'data-action' => 'input->material-dynamic-form#calculateUnitPrice change->material-dynamic-form#calculateUnitPrice'
                ]
            ])
            ->add('discountedPrice', TextType::class, [
                'label' => 'Coste con Descuento',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control font-weight-bold',
                    'type' => 'text',
                    'readonly' => true,
                    'data-material-dynamic-form-target' => 'discountedPriceInput'
                ]
            ])
            ->add('iva', TextType::class, [
                'label' => 'IVA (%)',
                'required' => false,
                'data' => 21,
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'text',
                    'data-action' => 'input->material-dynamic-form#performCalculations'
                ]
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
                'required' => true,
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
            ->add('networkId', TextType::class, [
                'label' => 'ID de Red (ISSI/IMEI)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'ISSI, IMEI, MMSI o TEI'
                ]
            ])
            ->add('hasMicrophone', CheckboxType::class, [
                'label' => 'Microfonía',
                'required' => false,
                'attr' => ['class' => 'form-check-input', 'data-action' => 'change->material-dynamic-form#handleMaintenanceSync']
            ])
            ->add('frequencyBand', TextType::class, [
                'label' => 'Banda de Frecuencia',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ej: VHF, UHF, TETRA...']
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Nº Teléfono',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => '+34...']
            ])
        ;

        $spanishNumericTransformer = new CallbackTransformer(
            function ($value) {
                // Transform normalized form data (float/int) to formatted Spanish string for the view
                if ($value === null || $value === '') return '';
                return number_format($value, 2, ',', '.');
            },
            function ($value) {
                // Transform formatted string from the view back to normalized form data
                if ($value === null || $value === '') return null;
                // Strip thousand separators (dots)
                $value = str_replace('.', '', $value);
                // Replace decimal separator (comma) with dot
                $value = str_replace(',', '.', $value);
                return (float) $value;
            }
        );

        $spanishIntegerTransformer = new CallbackTransformer(
            function ($value) {
                if ($value === null || $value === '') return '';
                return number_format($value, 0, ',', '.');
            },
            function ($value) {
                if ($value === null || $value === '') return null;
                $value = str_replace('.', '', $value);
                return (int) preg_replace('/[^0-9]/', '', $value);
            }
        );

        $builder->get('safetyStock')->addModelTransformer($spanishIntegerTransformer);
        $builder->get('unitsPerPackage')->addModelTransformer($spanishIntegerTransformer);
        $builder->get('numPackages')->addModelTransformer($spanishIntegerTransformer);
        $builder->get('stock')->addModelTransformer($spanishIntegerTransformer);
        $builder->get('iva')->addModelTransformer($spanishIntegerTransformer);

        $builder->get('unitPrice')->addModelTransformer($spanishNumericTransformer);
        $builder->get('totalPrice')->addModelTransformer($spanishNumericTransformer);
        $builder->get('discountPercentage')->addModelTransformer($spanishNumericTransformer);
        $builder->get('discountedPrice')->addModelTransformer($spanishNumericTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Material::class,
        ]);
    }
}
