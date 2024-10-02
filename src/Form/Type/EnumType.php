<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnumType extends AbstractType
{
    private string $enumClass;

    public function __construct(string $enumClass)
    {
        $this->enumClass = $enumClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', ChoiceType::class, [
            'choices' => $this->getChoices(),
            'choice_label' => function ($choice) {
                return ucfirst(strtolower($choice));
            },
            'label' => $options['label'],
            'placeholder' => $options['placeholder'],
        ]);
    }

    private function getChoices(): array
    {
        $choices = [];
        foreach ($this->enumClass::cases() as $case) {
            $choices[$case->name] = $case->value;
        }
        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => null,
            'placeholder' => null,
        ]);

        $resolver->setRequired('enum_class');
    }
}
