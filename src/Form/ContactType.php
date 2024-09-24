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
use Symfony\Component\Validator\Constraints\NotBlank;

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
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}

