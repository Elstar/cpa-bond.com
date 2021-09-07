<?php

namespace App\Form\Webmaster;

use App\Entity\Postback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostbackFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('leadCreate', null, [
                'label' => 'Lead create'
            ])
            ->add('leadApprove', null, [
                'label' => 'Lead approve'
            ])
            ->add('leadDecline', null, [
                'label' => 'Lead decline'
            ])
            ->add('leadTrash', null, [
                'label' => 'Trash lead'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Postback::class,
        ]);
    }
}
