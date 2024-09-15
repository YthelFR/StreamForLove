<?php

namespace App\Form;

use App\Entity\Articles;
use Ehyiah\QuillJsBundle\DTO\Fields\BlockField\HeaderField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\BoldField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\ItalicField;
use Ehyiah\QuillJsBundle\DTO\QuillGroup;
use Ehyiah\QuillJsBundle\Form\QuillType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entete', null, [
                'label' => 'En-tête'
            ])
            ->add('titre', null, [
                'label' => 'Titre'
            ])
            ->add('accroche', QuillType::class, [
                'label' => 'Accroche',
                'quill_extra_options' => [
                    'height' => '400px',
                    'theme' => 'snow',
                    'placeholder' => 'Rédigez l\'accroche...',
                ],
                'quill_options' => [
                    QuillGroup::build(
                        new BoldField(),
                        new ItalicField()
                    ),
                    QuillGroup::build(
                        new HeaderField(HeaderField::HEADER_OPTION_1),
                        new HeaderField(HeaderField::HEADER_OPTION_2)
                    ),
                ]
            ])
            ->add('texte', QuillType::class, [
                'label' => 'Texte',
                'quill_extra_options' => [
                    'height' => '600px',
                    'theme' => 'snow',
                    'placeholder' => 'Écrivez le texte de l\'article...',
                ],
                'quill_options' => [
                    QuillGroup::buildWithAllFields()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
