<?php

namespace App\Form\Type;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalesType extends AbstractType
{

    private array $locales;

    public function __construct(ContainerInterface $container)
    {
        $this->locales = $container->getParameter('locales');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [
                array_combine($this->locales, $this->locales)
            ]
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}