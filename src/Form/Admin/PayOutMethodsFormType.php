<?php

namespace App\Form\Admin;

use App\Entity\PayOutMethods;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayOutMethodsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('active')
            ->add('convertRate', MoneyType::class, [
                'currency' => 'UAH',
                'scale' => 4
            ])
            ->add('commission', MoneyType::class, [
                'currency' => 'UAH',
                'scale' => 4
            ])
        ;

        $builder->get('active')
            ->addModelTransformer(new CallbackTransformer(
                function ($activeAsString) {
                    // transform the string to boolean
                    return (bool)(int)$activeAsString;
                },
                function ($activeAsBoolean) {
                    // transform the boolean to string
                    return (string)(int)$activeAsBoolean;
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PayOutMethods::class,
        ]);
    }
}
