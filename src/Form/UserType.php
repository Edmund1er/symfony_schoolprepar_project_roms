<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Filiere;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Élève' => 'ROLE_ELEVE',
                    'Mentor' => 'ROLE_MENTOR',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('filiere', EntityType::class, [
                'class' => Filiere::class,
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-control']
            ])
            ->add('evenements', EntityType::class, [
                'class' => Evenement::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'attr' => ['class' => 'form-control']
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