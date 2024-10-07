<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PronomsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pronoms', ChoiceType::class, [
                'choices' => [
                    'Il/Lui' => 'Il/Lui',
                    'Elle/Elle' => 'Elle/Elle',
                    'Iel/Iels' => 'Iel/Iels',
                    'Ils/Eux' => 'Ils/Eux',
                    'Elles/Eux' => 'Elles/Eux',
                ],
                'required' => false,
                'label' => 'Pronoms',
                'placeholder' => 'Sélectionnez vos pronoms',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
