<?php

namespace App\Form;

use App\Entity\Outsiders;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Url;

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
                    new Url(['message' => 'L\'URL Twitch doit être valide.']),
                ],
            ])
            ->add('somme', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'La somme ne peut pas être vide.']),
                    new Positive(['message' => 'La somme doit être un nombre positif.']),
                    new Type(['type' => 'integer', 'message' => 'La somme doit être un entier.']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Outsiders::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'outsiders_item',
        ]);
    }
}
