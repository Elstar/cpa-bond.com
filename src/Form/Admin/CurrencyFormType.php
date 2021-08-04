<?php

namespace App\Form\Admin;

use App\Entity\Currency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CurrencyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label_attr' => [
                    'class' => 'sr-only'
                ],
                'attr' => [
                    'placeholder' => 'name',
                    'class' => 'form-control col-sm-12'
                ],
                'row_attr' => [
                    'class' => 'mb-2 col-sm-1'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'You did not enter a currency name'
                    ])
                ]
            ])
            ->add('sign', null, [
                'label_attr' => [
                    'class' => 'sr-only'
                ],
                'attr' => [
                    'placeholder' => 'sign',
                    'class' => 'form-control col-sm-7'
                ],
                'row_attr' => [
                    'class' => 'mx-sm-1 mb-2 col-sm-1'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'You did not enter a currency sign'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Currency::class,
        ]);
    }
}
