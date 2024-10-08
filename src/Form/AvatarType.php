<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (jpeg, png, gif).',
                    ])
                ],
            ])
            ->add('pronoms', ChoiceType::class, [
                'choices' => [
                    'Il/Lui' => 'Il/Lui',
                    'Elle/Elle' => 'Elle/Elle',
                    'Iel/Iels' => 'Iel/Iels',
                    'Ils/Eux' => 'Ils/Eux',
                    'Elles/Eux' => 'Elles/Eux',
                ],
                'required' => false,
                'label' => 'Pronoms',
                'placeholder' => 'Sélectionnez vos pronoms',
            ])
            ->add('lien', TextType::class, [
                'label' => 'Lien de la cagnotte',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'avatar_form',
        ]);
    }
}
