<?php

namespace App\Form;

use App\Entity\Species;
use App\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;

class AssoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('name', TextType::class, ['label' => 'Nom de l\'association','attr' => ['placeholder' => 'saisissez votre nom']])
            ->add('siret', IntegerType::class, [
                'constraints' => [new Positive()],
                    'attr' => [
                        'maxlength' => 9
                        ]
                ])
            ->add('mail', EmailType::class, ['label' => 'E-Mail','attr' => ['placeholder' => 'saisissez votre e-mail']])
            ->add('password', TextType::class, ['label' => 'Mot de passe'])
            ->add('adress', TextType::class, ['label' => 'Adresse'])
            ->add('zipcode', TextType::class, ['label' => 'Code postal'])
            ->add('city', TextType::class, ['label' => 'Ville'])
            ->add('department', TextType::class, ['label' => 'Département'])
            ->add('region', TextType::class, ['label' => 'Région'])
            ->add('phone_number', TextType::class, ['label' => 'Numero de téléphone'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('picture', TextareaType::class, ['label' => 'Photo'])
            ->add('website', TextareaType::class, ['label' => 'Site web'])
            ->add('roles', CollectionType ::class, ['label' => 'Rôle'])
            ->add('species', EntityType::class, [
                'class' => Species::class,
                'choice_label' => 'name',
                'multiple' => true, 
                'expanded' => true,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
