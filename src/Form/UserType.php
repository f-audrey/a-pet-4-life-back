<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('name')
            ->add('firstname')
            ->add('lastname')
            ->add('siret')
            ->add('mail')
            ->add('password')
            ->add('adress')
            ->add('zipcode')
            ->add('city')
            ->add('department')
            ->add('region')
            ->add('phone_number')
            ->add('description')
            ->add('status')
            ->add('picture')
            ->add('website')
            ->add('roles')
            ->add('slug')
            ->add('species')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
