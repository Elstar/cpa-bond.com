<?php

namespace App\Form\Admin;

use App\Entity\Category;
use App\Entity\PreLandingPage;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreLandingPageFormType extends AbstractType
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var PreLandingPage $platform
         */
        $platform = $options['data'];

        $builder
            ->add('name')
            ->add('url')
            ->add('category', HiddenType::class, [
                'mapped' => false,
                'data' => $platform->getCategory()->getId()
            ])
            /*->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return sprintf('%s', str_repeat("▬", $category->getLvl()) . $category->getName());
                },
                'choices' => $this->categoryRepository->getTree(),
                'attr' => [
                    'readonly' => 'disabled',
                    'class' => 'bg-dark'
                ]
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PreLandingPage::class,
        ]);
    }
}
