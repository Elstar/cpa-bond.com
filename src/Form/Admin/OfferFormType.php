<?php

namespace App\Form\Admin;

use App\Entity\Category;
use App\Entity\Currency;
use App\Entity\Geo;
use App\Entity\Landing;
use App\Entity\Offer;
use App\Entity\PayTypes;
use App\Entity\PreLanding;
use App\Entity\PreLandingPage;
use App\Form\Type\CategoryType;
use App\Form\Type\LocalesType;
use App\Repository\CategoryRepository;
use App\Repository\CurrencyRepository;
use App\Repository\GeoRepository;
use App\Repository\LandingRepository;
use App\Repository\PayTypesRepository;
use App\Repository\PreLandingPageRepository;
use App\Repository\PreLandingRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class OfferFormType extends AbstractType
{

    private GeoRepository $geoRepository;
    private CurrencyRepository $currencyRepository;
    private PayTypesRepository $payTypesRepository;
    private CategoryRepository $categoryRepository;
    private PreLandingRepository $preLandingRepository;
    private LandingRepository $landingRepository;
    private PreLandingPageRepository $preLandingPageRepository;

    public function __construct(
        GeoRepository $geoRepository,
        CurrencyRepository $currencyRepository,
        PayTypesRepository $payTypesRepository,
        CategoryRepository $categoryRepository,
        PreLandingRepository $preLandingRepository,
        LandingRepository $landingRepository,
        PreLandingPageRepository $preLandingPageRepository
    ) {
        $this->geoRepository = $geoRepository;
        $this->currencyRepository = $currencyRepository;
        $this->payTypesRepository = $payTypesRepository;
        $this->categoryRepository = $categoryRepository;
        $this->preLandingRepository = $preLandingRepository;
        $this->landingRepository = $landingRepository;
        $this->preLandingPageRepository = $preLandingPageRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var Offer $offer
         */
        $offer = $options['data'] ?? null;

        $imageConstraints = [
            new Image([
                'allowSquare' => true
            ])
        ];

        if (!$offer || !$offer->getImageFilename()) {
            $imageConstraints[] = new NotNull([
                'message' => 'You did not choose an image of offer'
            ]);
        }

        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return sprintf('%s', str_repeat("▬", $category->getLvl()) . $category->getName());
                },
                'choices' => $this->categoryRepository->getTree(),
                'attr' => [
                    'class' => 'bg-dark'
                ]
            ])
            ->add('name')
            ->add('geo', EntityType::class, [
                'class' => Geo::class,
                'choice_label' => function (Geo $geo) {
                    return sprintf('%s', $geo->getName());
                },
                'choices' => $this->geoRepository->findAll(),
                'multiple' => true,
                'label_attr' => [
                    'class' => 'bg-dark'
                ],
                'attr' => [
                    'class' => 'js-example-basic-multiple bg-dark'
                ]
            ])
            ->add('currency', EntityType::class, [
                'class' => Currency::class,
                'choices' => $this->currencyRepository->findAll(),
                'choice_label' => function (Currency $currency) {
                    return sprintf('%s', $currency->getName());
                },
                'attr' => [
                    'class' => 'bg-dark'
                ]
            ])
            ->add('payType', EntityType::class, [
                'class' => PayTypes::class,
                'choice_label' => function (PayTypes $payTypes) {
                    return sprintf('%s', $payTypes->getName());
                },
                'choices' => $this->payTypesRepository->findAll(),
                'multiple' => true,
                'label_attr' => [
                    'class' => 'bg-dark'
                ],
                'attr' => [
                    'class' => 'js-example-basic-multiple'
                ]
            ])
            ->add('preLanding', EntityType::class, [
                'class' => PreLanding::class,
                'choice_label' => function (PreLanding $preLanding) {
                    return sprintf('%s', $preLanding->getName());
                },
                'choices' => $this->preLandingRepository->findAll(),
                'multiple' => true,
                'label_attr' => [
                    'class' => 'bg-dark'
                ],
                'attr' => [
                    'class' => 'js-example-basic-multiple'
                ]
            ])
            ->add('landing', EntityType::class, [
                'class' => Landing::class,
                'choice_label' => function (Landing $landing) {
                    return sprintf('%s', $landing->getName());
                },
                'choices' => $this->landingRepository->findAll(),
                'multiple' => true,
                'label_attr' => [
                    'class' => 'bg-dark'
                ],
                'attr' => [
                    'class' => 'js-example-basic-multiple'
                ]
            ])
            ->add('preLandingPage', EntityType::class, [
                'class' => PreLandingPage::class,
                'choice_label' => function (PreLandingPage $preLandingPage) {
                    return sprintf('%s', $preLandingPage->getName());
                },
                'choices' => $this->preLandingPageRepository->findAll(),
                'multiple' => true,
                'label_attr' => [
                    'class' => 'bg-dark'
                ],
                'attr' => [
                    'class' => 'js-example-basic-multiple'
                ]
            ])
            ->add('geoInfo')
            ->add('sourceTraffic')
            ->add('forbiddenSources')
            ->add('paySum')
            ->add('image', FileType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Please choose image'
                ],
                'constraints' => $imageConstraints
            ]);
        if (!empty($offer) && $offer->getId()) {
            $builder->add('locale', LocalesType::class, [
                'label' => 'Language',
                'data' => $offer->getLocale(),
                'attr' => [
                    'class' => 'bg-dark'
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
