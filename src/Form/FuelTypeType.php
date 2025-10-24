<?php

namespace App\Form;

use App\Entity\FuelType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for creating and editing FuelType entities.
 */
class FuelTypeType extends AbstractType
{
    /**
     * Builds the form structure.
     *
     * @param FormBuilderInterface $builder The form builder.
     * @param array $options The options for building the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre del Tipo de Combustible',
                'required' => true,
            ]);
    }

    /**
     * Configures the options for this form type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FuelType::class,
        ]);
    }
}