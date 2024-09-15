<?php

namespace App\Form;

use App\Entity\Presentations;
use Ehyiah\QuillJsBundle\DTO\Fields\BlockField\HeaderField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\BoldField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\ItalicField;
use Ehyiah\QuillJsBundle\DTO\QuillGroup;
use Symfony\Component\Form\AbstractType;
use Ehyiah\QuillJsBundle\Form\QuillType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PresentationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture', null, [
                'label' => 'Image de présentation'
            ])
            ->add('question1', QuillType::class, [
                'label' => 'Question 1',
                'quill_extra_options' => [
                    'height' => '400px',
                    'theme' => 'snow',
                    'placeholder' => 'Écrivez la réponse...',
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
            ->add('question2', QuillType::class, [
                'label' => 'Question 2',
                'quill_extra_options' => [
                    'height' => '400px',
                    'theme' => 'snow',
                    'placeholder' => 'Écrivez la réponse...',
                ],
                'quill_options' => [
                    QuillGroup::buildWithAllFields()
                ]
            ])
            ->add('question3', QuillType::class, [
                'label' => 'Question 3',
                'quill_extra_options' => [
                    'height' => '400px',
                    'theme' => 'snow',
                    'placeholder' => 'Écrivez la réponse...',
                ],
                'quill_options' => [
                    QuillGroup::buildWithAllFields()
                ]
            ])
            ->add('clip1', null, [
                'label' => 'Clip 1'
            ])
            ->add('clip2', null, [
                'label' => 'Clip 2'
            ])
            ->add('clip3', null, [
                'label' => 'Clip 3'
            ])
            ->add('clip4', null, [
                'label' => 'Clip 4'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Presentations::class,
        ]);
    }
}
