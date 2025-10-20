<?php

namespace App\Form;

use App\Form\Type\FloatingLabelTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\Type\FloatingLabelEmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dni', FloatingLabelTextType::class, [
                'label' => 'DNI',
            ])
            ->add('nombre', FloatingLabelTextType::class, [
                'label' => 'Nombre',
            ])
            ->add('apellido', FloatingLabelTextType::class, [
                'label' => 'Apellido',
            ])
            ->add('email', FloatingLabelEmailType::class, [
                'label' => 'Correo Electrónico',
            ]);
    }
}
