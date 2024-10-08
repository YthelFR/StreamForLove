<?php

namespace App\Form;

use App\Entity\Evenements;
use App\Entity\EvenementsClips;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementsClipsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', TextType::class, [
                'label' => 'URL du clip',
                'attr' => ['placeholder' => 'Entrez l\'URL du clip']
            ])
            ->add('evenement', EntityType::class, [
                'class' => Evenements::class,
                'choice_label' => 'annee', 
                'placeholder' => 'Sélectionnez un événement',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EvenementsClips::class,
        ]);
    }
}
