<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Material;
use App\Entity\MaterialUnit;
use App\Entity\MaterialBatch;
use App\Entity\Volunteer;
use Doctrine\ORM\EntityRepository;
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
                'query_builder' => function (EntityRepository $er) use ($material) {
                    $qb = $er->createQueryBuilder('l');
                    if ($material) {
                        if ($material->getNature() === Material::NATURE_CONSUMABLE) {
                            $qb->join('l.stocks', 's')
                                ->where('s.material = :material')
                                ->andWhere('s.quantity > 0')
                                ->setParameter('material', $material);
                        } else {
                            $qb->join('l.units', 'u')
                                ->where('u.material = :material')
                                ->setParameter('material', $material);
                        }
                    }
                    return $qb->orderBy('l.name', 'ASC');
                },
                'choice_label' => function (Location $location) use ($material) {
                    $label = $location->getName();
                    if ($material && $material->getNature() === Material::NATURE_CONSUMABLE) {
                        $qty = 0;
                        foreach ($location->getStocks() as $s) {
                            if ($s->getMaterial() === $material) $qty += $s->getQuantity();
                        }
                        $label .= sprintf(' (Disp: %d)', $qty);
                    }
                    return $label;
                },
                'label' => 'Ubicación de Origen',
                'required' => false,
                'placeholder' => 'Proveedor (Entrada)',
                'attr' => ['class' => 'form-select']
            ])
            ->add('destination', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
                'label' => 'Ubicación de Destino',
                'required' => false,
                'placeholder' => 'Fuera del sistema (Baja/Consumo)',
                'attr' => ['class' => 'form-select']
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Cantidad',
                'attr' => ['class' => 'form-control', 'min' => 1]
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

        if ($material) {
            if ($material->getNature() === Material::NATURE_TECHNICAL) {
                $builder->add('materialUnit', EntityType::class, [
                    'class' => MaterialUnit::class,
                    'choices' => $material->getUnits(),
                    'choice_label' => function (MaterialUnit $unit) {
                        return sprintf('%s - %s (%s)', $unit->getSerialNumber() ?: 'S/N', $unit->getAlias() ?: 'Sin Alias', $unit->getLocation() ? $unit->getLocation()->getName() : 'Sin ubicación');
                    },
                    'label' => 'Unidad Específica (Activo)',
                    'required' => false,
                    'placeholder' => 'Seleccionar unidad...',
                    'attr' => ['class' => 'form-select']
                ]);
            } else {
                $builder->add('batch', EntityType::class, [
                    'class' => MaterialBatch::class,
                    'query_builder' => function (EntityRepository $er) use ($material) {
                        return $er->createQueryBuilder('b')
                            ->where('b.material = :material')
                            ->setParameter('material', $material)
                            ->orderBy('b.expirationDate', 'ASC');
                    },
                    'choice_label' => function (MaterialBatch $batch) {
                        return sprintf('Lote: %s (Exp: %s)', $batch->getBatchNumber(), $batch->getExpirationDate() ? $batch->getExpirationDate()->format('d/m/Y') : 'N/A');
                    },
                    'label' => 'Lote Específico',
                    'required' => false,
                    'placeholder' => 'Automático (FIFO)',
                    'attr' => ['class' => 'form-select']
                ]);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'material' => null,
        ]);
    }
}
