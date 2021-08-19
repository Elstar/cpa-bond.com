<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Geo;
use App\Form\Model\OfferFilterFormModel;
use App\Repository\CategoryRepository;
use App\Repository\GeoRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferFilterFormType extends AbstractType
{


    private GeoRepository $geoRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(GeoRepository $geoRepository, CategoryRepository $categoryRepository)
    {
        $this->geoRepository = $geoRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label_attr' => [
                    'class' => 'sr-only',
                    'placeholder' => 'Offer name'
                ],
                'attr' => [
                    'class' => 'w-100'
                ],
                'row_attr' => [
                    'class' => 'col-3'
                ]
            ])
            ->add('geo', EntityType::class, [
                'class' => Geo::class,
                'multiple' => true,
                'choice_label' => function(Geo $geo) {
                    return sprintf('%s (%s)', $geo->getCountry(), $geo->getName());
                },
                'choices' => $this->geoRepository->findAll(),
                'label_attr' => [
                    'class' => 'bg-dark'
                ],
                'attr' => [
                    'class' => 'js-example-basic-multiple bg-dark w-100'
                ],
                'label_attr' => [
                    'class' => 'sr-only',
                    'placeholder' => 'Choose geo'
                ],
                'row_attr' => [
                    'class' => 'col-2'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => function(Category $category) {
                    return sprintf('%s %s', str_repeat("▬", $category->getLvl()), $category->getName());
                },
                'choices' => $this->categoryRepository->getTree(),
                'attr' => [
                    'class' => 'js-example-basic-single bg-dark w-100 form-control-lg'
                ],
                'label_attr' => [
                    'class' => 'sr-only',
                ],
                'row_attr' => [
                    'class' => 'col-2'
                ],
                'placeholder' => 'Choose category',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            OfferFilterFormModel::class
        ]);
    }
}
