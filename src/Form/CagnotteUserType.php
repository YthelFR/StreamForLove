<?php

namespace App\Form;

use App\Entity\Cagnotte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CagnotteUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lien', TextType::class, [
                'label' => 'Lien de la cagnotte',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez le lien de votre cagnotte'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cagnotte::class,
        ]);
    }
}
