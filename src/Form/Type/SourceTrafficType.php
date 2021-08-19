<?php

namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SourceTrafficType extends AbstractType
{

    private array $sources = [
        0 => "Teaser networks",
        1 => "Own site",
        2 => "Instagram",
        3 => "Facebook",
        4 => "Search",
        5 => "Video advertisement"
    ];

    public function __construct(TranslatorInterface $translator)
    {
        foreach ($this->sources as &$source) {
            $source = $translator->trans($source);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => array_combine(array_values($this->sources), array_keys($this->sources)),
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}