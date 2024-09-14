<?php

// src/Form/SocialsNetworkType.php


namespace App\Form;

use App\Entity\SocialsNetwork;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocialsNetworkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', ChoiceType::class, [
                'choices' => [
                    'Twitter' => 'twitter',
                    'Facebook' => 'facebook',
                    'Instagram' => 'instagram',
                    'TikTok' => 'tiktok',
                    'YouTube' => 'youtube',
                    'Twitch' => 'twitch',
                    'Discord' => 'discord',
                ],
                'choice_label' => function ($choice) {
                    return ucfirst($choice);
                },
                'label' => 'Réseau social',
                'placeholder' => 'Choisissez un réseau social',
            ])
            ->add('url', UrlType::class, [
                'label' => 'URL',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SocialsNetwork::class,
        ]);
    }
}
