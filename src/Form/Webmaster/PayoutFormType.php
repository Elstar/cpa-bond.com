<?php

namespace App\Form\Webmaster;

use App\Entity\PaymentSystem;
use App\Entity\Payout;
use App\Repository\PaymentSystemRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class PayoutFormType extends AbstractType
{


    private Security $security;
    private PaymentSystemRepository $paymentSystemRepository;

    public function __construct(Security $security, PaymentSystemRepository $paymentSystemRepository)
    {
        $this->security = $security;
        $this->paymentSystemRepository = $paymentSystemRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getUser();

        $builder
            ->add('sum', MoneyType::class, [
                'currency' => 'UAH',
                'scale' => 0,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter sum'
                    ]),
                    new GreaterThan([
                        'value' => 499,
                        'message' => 'Please enter sum more than 499 UAH'
                    ])
                ]
            ])
            ->add('paymentSystem', EntityType::class, [
                'class' => PaymentSystem::class,
                'choice_label' => function(PaymentSystem $paymentSystem) {
                    return sprintf('%s', $paymentSystem->getPayoutMethod()->getName());
                },
                'choices' => $this->paymentSystemRepository->getUserPaymentSystems($user)
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Payout::class,
        ]);
    }
}
