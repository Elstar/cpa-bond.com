<?php

namespace App\Form\Admin;


use App\Entity\PreLanding;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreLandingFormType extends AbstractType
{


    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var PreLanding $platform
         */
        $platform = $options['data'];

        $builder
            ->add('name')
            ->add('url')
            ->add('downloadUrl')
            ->add('category', HiddenType::class, [
                'mapped' => false,
                'data' => $platform->getCategory()->getId()
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PreLanding::class,
        ]);
    }
}
