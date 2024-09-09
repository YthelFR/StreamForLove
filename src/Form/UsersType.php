<?php

namespace App\Form;

use App\Entity\Users;
use App\Enum\SocialsNetworkTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('pseudo')
            ->add('avatar')
            ->add('isValid')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('socialNetworks', CollectionType::class, [
                'entry_type' => SocialsNetworkTypeEnum::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Réseaux sociaux',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
