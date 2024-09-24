<?php

namespace App\Form;

use App\Entity\Outsiders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class OutsidersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le pseudo ne peut pas être vide.']),
                    new Length(['max' => 50, 'maxMessage' => 'Le pseudo ne peut pas dépasser {{ limit }} caractères.']),
                ],
            ])
            ->add('twitch', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le champ Twitch ne peut pas être vide.']),
                    new Length(['max' => 255, 'maxMessage' => 'Le champ Twitch ne peut pas dépasser {{ limit }} caractères.']),
                ],
            ])
            ->add('somme', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'La somme ne peut pas être vide.']),
                    new Positive(['message' => 'La somme doit être un nombre positif.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Outsiders::class,
        ]);
    }
}
