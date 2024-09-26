<?php

namespace App\Form;

use App\Entity\Evenements;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class EvenementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('annee', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('donations', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('participants', EntityType::class, [
                'class' => Users::class,
                'multiple' => true,
                'expanded' => true, // Affiche une liste de cases à cocher
                'choice_label' => 'pseudo',
            ])
            ->add('thumbnail', FileType::class, [
                'label' => 'Image (Thumbnail)',
                'mapped' => false, // Ce champ n'est pas associé à une propriété de l'entité directement
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG ou PNG)',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenements::class,
        ]);
    }
}
