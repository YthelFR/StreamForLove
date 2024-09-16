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
            'mapped' => false, 
            'required' => false,
            'attr' => [
                'accept' => 'image/*',
            ],
        ])
            ->add('question1', null, [
                'label' => 'Peux-tu te présenter en quelques mots ?'
            ])
            ->add('question2', null, [
                'label' => 'Comment en es-tu arrivé l\'univers du streaming ?'
            ])
            ->add('question3', null, [
                'label' => 'Pourquoi faire un stream caritatif ?'
            ])
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
            ])
            ->add('planning', FileType::class, [
                'label' => 'Planning',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => 'image/*',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Presentations::class,
        ]);
    }
}
