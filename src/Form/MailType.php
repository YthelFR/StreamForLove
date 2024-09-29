<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role', ChoiceType::class, [
                'label' => 'Sélectionner un Rôle',
                'choices' => [
                    'Tous les utilisateurs' => 'all',
                    'Anciens streamers' => 'ROLE_STREAMER_ABSENT',
                    'Streamers actifs' => 'ROLE_STREAMER_ACTIF',
                    'Managers' => 'ROLE_MANAGER',
                ],
                'placeholder' => 'Choisissez un rôle',
                'attr' => ['class' => 'mb-4 select2'],
                'required' => false,
            ])
            ->add('recipient', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'email',
                'label' => 'Choisissez un ou plusieurs Utilisateurs',
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'select2 mb-4',
                    'style' => 'display:none;', // Assurez-vous que c'est ici
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => 'Objet',
                'attr' => ['placeholder' => 'Entrez l\'objet du message', 'class' => 'mb-4 p-2 border border-gray-300 rounded'],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => ['placeholder' => 'Entrez votre message', 'class' => 'mb-4 p-2 border border-gray-300 rounded'],
            ])
            ->add('senderName', TextType::class, [
                'label' => 'Nom de l\'Expéditeur',
                'attr' => ['placeholder' => 'Entrez votre nom', 'class' => 'mb-4 p-2 border border-gray-300 rounded'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
