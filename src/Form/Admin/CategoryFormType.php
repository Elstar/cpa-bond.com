<?php

namespace App\Form\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\Entity\Category;
use App\Entity\CategoryTranslation;
use App\Validator\UniqueCategory;
use Gedmo\Translator\Translation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parentId', HiddenType::class)
            /*->add('name', TextType::class, [
                'label' => 'Название категории',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Вы не ввели название категории'
                    ]),
                    new UniqueCategory()
                ]
            ])*/
            ->add('translations', 'translatable', [
                'field' => 'name',
                'property_path' => 'translations',
                'personal_translation' => CategoryTranslation::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
