<?php

namespace App\Form;

use App\Entity\Presentations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PresentationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picturePath', FileType::class, [
                'label' => 'Image de présentation',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG ou PNG).',
                    ])
                ],
            ])
            ->add('planning', FileType::class, [
                'label' => 'Planning',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF valide.',
                    ])
                ],
            ])
            ->add('goals', FileType::class, [
                'label' => 'Objectifs',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF valide.',
                    ])
                ],
            ])

            // Validation des champs textuels pour les questions
            ->add('question1', TextType::class, [
                'label' => 'Peux-tu te présenter en quelques mots ?',
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'La réponse ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('question2', TextType::class, [
                'label' => 'Comment en es-tu arrivé à l\'univers du streaming ?',
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'La réponse ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('question3', TextType::class, [
                'label' => 'Pourquoi faire un stream caritatif ?',
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'La réponse ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])

            // Validation des clips
            ->add('clip1', TextType::class, [
                'label' => 'Clip 1',
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le lien ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^(https?:\/\/)?(www\.)?(twitch\.tv|youtube\.com)\/.*$/',
                        'message' => 'Veuillez entrer un lien valide vers Twitch ou YouTube.',
                    ]),
                ],
            ])
            ->add('clip2', TextType::class, [
                'label' => 'Clip 2',
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le lien ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^(https?:\/\/)?(www\.)?(twitch\.tv|youtube\.com)\/.*$/',
                        'message' => 'Veuillez entrer un lien valide vers Twitch ou YouTube.',
                    ]),
                ],
            ])
            ->add('clip3', TextType::class, [
                'label' => 'Clip 3',
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le lien ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^(https?:\/\/)?(www\.)?(twitch\.tv|youtube\.com)\/.*$/',
                        'message' => 'Veuillez entrer un lien valide vers Twitch ou YouTube.',
                    ]),
                ],
            ])
            ->add('clip4', TextType::class, [
                'label' => 'Clip 4',
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le lien ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^(https?:\/\/)?(www\.)?(twitch\.tv|youtube\.com)\/.*$/',
                        'message' => 'Veuillez entrer un lien valide vers Twitch ou YouTube.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Presentations::class,
        ]);
    }
}
