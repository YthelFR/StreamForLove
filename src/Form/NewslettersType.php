<?php

namespace App\Form;

use App\Entity\Newsletters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewslettersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sujet', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le sujet ne peut pas être vide.']),
                ],
            ])
            ->add('contenu', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le contenu ne peut pas être vide.']),
                ],
            ])
            ->add('dateEnvoi', DateTimeType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date d\'envoi ne peut pas être vide.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Newsletters::class,
        ]);
    }
}
