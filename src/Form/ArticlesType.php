<?php

namespace App\Form;

use App\Entity\Articles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('entete', FileType::class, [
            'label' => 'Image de présentation',
            'mapped' => false,
            'required' => false,
            'attr' => [
            'accept' => 'image/*',
            ],
        ])
        ->add('titre', null, [
            'label' => 'Titre'
        ])
        ->add('accroche')
        ->add('texte');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
