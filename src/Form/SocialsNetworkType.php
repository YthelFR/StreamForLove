<?php

namespace App\Form;

use App\Entity\SocialsNetwork;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\SocialsNetworkTypeEnum;

class SocialsNetworkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', ChoiceType::class, [
            'choices' => SocialsNetworkTypeEnum::choices(),
            'label' => 'Réseau social',
            'placeholder' => 'Choisissez un réseau social',
        ])
        ->add('url', TextType::class, [
            'label' => 'URL du profil',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SocialsNetwork::class,
        ]);
    }
}
