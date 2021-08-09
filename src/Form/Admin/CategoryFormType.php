<?php

namespace App\Form\Admin;

use App\Entity\Category;
use App\Form\Type\LocalesType;
use App\Validator\UniqueCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var Category $category
         */
        $category = $options['data'] ?? null;

        $parentId = $category->getParent() ? $category->getParent()->getId() : 0;

        $builder
            ->add('parent', HiddenType::class, [
                'mapped' => false,
                'data' => $parentId
            ])
            ->add('name', TextType::class, [
                'label' => 'Category name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'You did not enter a category name'
                    ]),
                    new UniqueCategory()
                ]
            ]);
        if (!empty($category) && $category->getId()) {
            $builder->add('locale', LocalesType::class, [
                'label' => 'Language',
                'data' => $category->getLocale(),
                'attr' => [
                    'class' => 'bg-dark'
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
