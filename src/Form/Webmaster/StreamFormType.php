<?php

namespace App\Form\Webmaster;

use App\Entity\Geo;
use App\Entity\Landing;
use App\Entity\PayTypes;
use App\Entity\PreLanding;
use App\Entity\PreLandingPage;
use App\Entity\Stream;
use App\Form\Type\SourceTrafficType;
use App\Repository\GeoRepository;
use App\Repository\PayTypesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StreamFormType extends AbstractType
{
    private GeoRepository $geoRepository;
    private PayTypesRepository $payTypesRepository;

    public function __construct(GeoRepository $geoRepository, PayTypesRepository $payTypesRepository)
    {
        $this->geoRepository = $geoRepository;
        $this->payTypesRepository = $payTypesRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var Stream $stream
         */
        $stream = $options['data'] ?? null;

        $builder
            ->add('name')
            ->add('sourceTraffic', SourceTrafficType::class, [
                'attr' => [
                    'class' => 'bg-dark'
                ]
            ])
            ->add('postbackCreate')
            ->add('postbackApprove')
            ->add('postbackDecline')
            ->add('postbackTrash')
            ->add('googleTagId')
            ->add('googleTagConversionId')
            ->add('preLanding', EntityType::class, [
                'required' => false,
                'class' => PreLanding::class,
                'choice_label' => function(PreLanding $preLanding) {
                    return sprintf('%s (cr 1:%s) %s', $preLanding->getName(), $preLanding->getCr(), $preLanding->getUrl());
                },
                'choices' => $stream->getOffer()->getPreLanding(),
                'expanded' => true,
                'label_attr' => [
                    'class' => 'text-white'
                ]
            ])
            ->add('landing', EntityType::class, [
                'required' => false,
                'class' => Landing::class,
                'choice_label' => function(Landing $landing) {
                    return sprintf('%s (cr 1:%s) %s', $landing->getName(), $landing->getCr(), $landing->getUrl());
                },
                'choices' => $stream->getOffer()->getLanding(),
                'expanded' => true,
                'label_attr' => [
                    'class' => 'text-white'
                ]
            ])
            ->add('preLandingPage', EntityType::class, [
                'required' => false,
                'class' => PreLandingPage::class,
                'choice_label' => function(PreLandingPage $preLandingPage) {
                    return sprintf('%s (cr 1:%s) %s', $preLandingPage->getName(), $preLandingPage->getCr(), $preLandingPage->getUrl());
                },
                'choices' => $stream->getOffer()->getPreLandingPage(),
                'expanded' => true,
                'label_attr' => [
                    'class' => 'text-white'
                ]
            ])
            ->add('geo', EntityType::class, [
                'class' => Geo::class,
                'choice_label' => function (Geo $geo) {
                    return sprintf('%s', $geo->getName());
                },
                'choices' => $this->geoRepository->findAll(),
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
                'attr' => [
                    'class' => 'bg-dark'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stream::class,
        ]);
    }
}
