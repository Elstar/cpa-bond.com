<?php

namespace App\Form\Admin;

use App\Entity\PaymentDetail;
use App\Form\Type\LocalesType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PaymentDetailFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var PaymentDetail $paymentDetail
         */
        $paymentDetail = $options['data'] ?? null;

        $builder
            ->add('detailProperty')
        ;
        if (!empty($paymentDetail) && $paymentDetail->getId()) {
            $builder->add('locale', LocalesType::class, [
                'label' => 'Language',
                'data' => $paymentDetail->getLocale(),
                'attr' => [
                    'class' => 'bg-dark'
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PaymentDetail::class,
        ]);
    }
}
