<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Material;
use App\Entity\MaterialUnit;
use App\Entity\Volunteer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterialTransferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $material = $options['material'];

        $builder
            ->add('origin', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
                'label' => 'Ubicación de Origen',
                'required' => false,
                'placeholder' => 'Proveedor (Entrada)',
                'attr' => ['class' => 'form-control']
            ])
            ->add('destination', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
                'label' => 'Ubicación de Destino',
                'required' => false,
                'placeholder' => 'Fuera del sistema (Baja/Consumo)',
                'attr' => ['class' => 'form-control']
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Cantidad',
                'attr' => ['class' => 'form-control', 'min' => 1]
            ])
            ->add('size', TextType::class, [
                'label' => 'Talla (si aplica)',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('reason', TextType::class, [
                'label' => 'Motivo',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ej: Reposición de ambulancia, Caducidad...']
            ])
            ->add('responsible', EntityType::class, [
                'class' => Volunteer::class,
                'choice_label' => 'name',
                'label' => 'Responsable del Movimiento',
                'attr' => ['class' => 'form-control']
            ]);

        if ($material && $material->getNature() === Material::NATURE_TECHNICAL) {
            $builder->add('materialUnit', EntityType::class, [
                'class' => MaterialUnit::class,
                'choices' => $material->getUnits(),
                'choice_label' => 'serialNumber',
                'label' => 'Unidad Específica (Activo)',
                'required' => false,
                'placeholder' => 'Seleccionar unidad...',
                'attr' => ['class' => 'form-control']
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'material' => null,
        ]);
    }
}
