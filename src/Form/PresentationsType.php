<?php

namespace App\Form;

use App\Entity\Presentations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PresentationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('picturePath', FileType::class, [
            'label' => 'Image de présentation',
            'mapped' => false, // Not mapped to the entity
            'required' => false,
            'attr' => [
                'accept' => 'image/*', // Optional: restrict to image files
            ],
        ])
            ->add('question1')
            ->add('question2')
            ->add('question3')
            ->add('clip1', null, [
                'label' => 'Clip 1'
            ])
            ->add('clip2', null, [
                'label' => 'Clip 2'
            ])
            ->add('clip3', null, [
                'label' => 'Clip 3'
            ])
            ->add('clip4', null, [
                'label' => 'Clip 4'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Presentations::class,
        ]);
    }
}
