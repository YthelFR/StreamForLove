<?php

// src/Form/ContactType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Votre nom'],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom ne peut pas être vide.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\s\'-]+$/',
                        'message' => 'Le nom ne peut contenir que des lettres, des espaces, des apostrophes ou des traits d’union.'
                    ])
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Votre adresse email'],
                'constraints' => [
                    new NotBlank(['message' => 'L\'adresse email ne peut pas être vide.']),
                    new Email(['message' => 'Veuillez entrer une adresse email valide.']),
                ],
            ])
            ->add('subject', ChoiceType::class, [
                'label' => 'Sujet',
                'choices' => [
                    'Demande de participation au prochain event' => 'inscriptionEvent',
                    'Demande de participation à une collecte hors event' => 'horsEvent',
                    'Demande d\'information' => 'info',
                    'Problème technique' => 'tech',
                    'Autre' => 'other',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner un sujet.']),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => ['placeholder' => 'Votre message'],
                'constraints' => [
                    new NotBlank(['message' => 'Le message ne peut pas être vide.']),
                    new Length([
                        'min' => 10,
                        'max' => 1000,
                        'minMessage' => 'Le message doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le message ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
