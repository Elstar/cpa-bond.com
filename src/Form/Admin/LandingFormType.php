<?php

namespace App\Form\Admin;


use App\Entity\Landing;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LandingFormType extends AbstractType
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var Landing $platform
         */
        $platform = $options['data'];

        $builder
            ->add('name')
            ->add('url')
            ->add('category', HiddenType::class, [
                'mapped' => false,
                'data' => $platform->getCategory()->getId()
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Landing::class,
        ]);
    }
}
