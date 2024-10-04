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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class EvenementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('annee', IntegerType::class, [
                'label' => 'Année',
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'année est requise.',
                    ]),
                ],
                'attr' => [
                    'min' => 1900, // Optionnel : pour limiter la saisie des années
                    'max' => date('Y'), // Optionnel : limite l'année au présent
                ],
            ])
            ->add('donations', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Range(['min' => 0, 'max' => 1000000, 'notInRangeMessage' => 'Le montant doit être entre 0 et 1 000 000']),
                ],
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 10,
                        'max' => 1000,
                        'minMessage' => 'La description doit contenir au moins 10 caractères',
                        'maxMessage' => 'La description ne peut contenir plus de 1000 caractères',
                    ]),
                ],
            ])
            ->add('participants', EntityType::class, [
                'class' => Users::class,
                'multiple' => true,
                'expanded' => false, // Assurez-vous que `expanded` est faux pour utiliser un select multiple
                'choice_label' => 'pseudo',
                'attr' => [
                    'class' => 'select2', // Ajout d'une classe pour utiliser select2
                    'multiple' => 'multiple', // S'assure que l'attribut multiple est bien activé
                ],
            ])
            ->add('thumbnail', FileType::class, [
                'label' => 'Image (Thumbnail)',
                'mapped' => false, // Ce champ n'est pas mappé à une propriété de l'entité directement
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
