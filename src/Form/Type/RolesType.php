<?php

namespace App\Form\Type;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RolesType extends AbstractType
{

    private array $roles;

    public function __construct(ContainerInterface $container)
    {
        $this->roles = $container->getParameter('security.role_hierarchy.roles');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => array_combine(array_keys($this->roles), array_keys($this->roles))
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}