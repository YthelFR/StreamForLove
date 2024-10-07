<?php

namespace App\Form;

use App\Entity\Cagnotte;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CagnotteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lien', TextType::class, [
                'label' => 'Lien de la cagnotte',
            ])
            ->add('user', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'pseudo',
                'label' => 'Utilisateur associé',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cagnotte::class,
        ]);
    }
}
