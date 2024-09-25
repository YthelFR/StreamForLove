<?php

namespace App\Form;

use App\Entity\Users;
use App\Form\SocialsNetworkType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'constraints' => new Email(['message' => 'Veuillez entrer une adresse email valide.']),
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                    'Manager' => 'ROLE_MANAGER',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('password')
            ->add('pseudo')
            ->add('avatar', FileType::class, [
                'label' => 'Avatar (Image file)',
                'required' => false, // Avatar peut être facultatif
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG ou GIF).',
                    ])
                ],
            ])
            ->add('isValid')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('socialsNetworks', CollectionType::class, [
                'entry_type' => SocialsNetworkType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Réseaux sociaux',
                'prototype' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
