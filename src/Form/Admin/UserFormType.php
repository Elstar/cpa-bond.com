<?php

namespace App\Form\Admin;

use App\Entity\Postback;
use App\Entity\User;
use App\Form\Type\RolesType;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{


    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var User $user
         */
        $user = $options['data'] ?? null;

        $builder
            ->add('roles', RolesType::class, [
                'data' => $user->getRoles(),
                'multiple' => true,
                'attr' => [
                    'class' => 'js-example-basic-multiple bg-dark'
                ],
                'label_attr' => [
                    'class' => 'bg-dark'
                ],
            ])
            ->add('telegram')
            ->add('viber')
            ->add('skype')
        ;

        $managers = $this->userRepository->findByRole('MANAGER');

        if (!empty($managers)) {
            $builder->add('manager', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return sprintf('%s %s', $user->getFirstName(), $user->getEmail());
                },
                'choices' => $this->userRepository->findByRole('MANAGER')
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
