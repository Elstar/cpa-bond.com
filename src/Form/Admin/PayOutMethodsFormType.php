<?php

namespace App\Form\Admin;

use App\Entity\PayOutMethods;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayOutMethodsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('convertRate', MoneyType::class, [
                'currency' => 'UAH',
                'scale' => 4
            ])
            ->add('commission', MoneyType::class, [
                'currency' => 'UAH',
                'scale' => 4
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PayOutMethods::class,
        ]);
    }
}
