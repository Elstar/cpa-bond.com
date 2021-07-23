<?php

namespace App\Form;

use App\Form\Model\UserRegistrationFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => false,
                'label' => 'Your name',
                'attr' => ['placeholder' => 'Your name'],
                'label_translation_parameters' => [
                    'First name' => 'First name'
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Your E-mail']
            ])
            ->add('plainPassword', RepeatedType::class, [
                'required' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match',
                'first_options' => [
                    'label' => 'Password',
                    'attr' => ['placeholder' => 'Password'],
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'attr' => ['placeholder' => 'Repeat Password'],
                ]
            ])
            ->add('telegram', TextType::class, [
                'required' => false,
                'label' => '@Telegram'
            ])
            ->add('viber', TextType::class, [
                'required' => false,
                'label' => 'Viber'
            ])
            ->add('skype', TextType::class, [
                'required' => false,
                'label' => 'Skype'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationFormModel::class,
        ]);
    }
}
