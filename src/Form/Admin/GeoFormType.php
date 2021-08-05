<?php

namespace App\Form\Admin;

use App\Entity\Geo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class GeoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var Geo $geo
         */
        $geo = $options['data'] ?? null;

        $imageConstraints = [
            new Image([
                'allowLandscape' => true
            ])
        ];

        if (! $geo || ! $geo->getImageFilename()) {
            $imageConstraints[] = new NotNull([
                'message' => 'You did not choose an image of flag'
            ]);
        }

        $builder
            ->add('image', FileType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Please choose image'
                ],
                'constraints' => $imageConstraints
            ])
            ->add('name')
            ->add('country')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Geo::class,
        ]);
    }
}
