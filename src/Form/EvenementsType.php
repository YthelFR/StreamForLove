<?php

namespace App\Form;

use App\Entity\Evenements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

class EvenementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom de l\'événement est requis.']),
                ],
                'attr' => ['class' => 'block w-full mt-1 p-2 bg-gray-100 border border-gray-300 outline-none'],
            ])
            ->add('date', null, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date est requise.']),
                    new DateTime(['message' => 'Veuillez entrer une date valide.']),
                ],
                'attr' => ['class' => 'block w-full mt-1 p-2 bg-gray-100 border border-gray-300 outline-none'],
            ])
            ->add('donations', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le montant des donations est requis.']),
                ],
            ])
            ->add('description', null, [
                'constraints' => [
                    new NotBlank(['message' => 'La description est requise.']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenements::class,
        ]);
    }
}
