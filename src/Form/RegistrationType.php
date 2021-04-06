<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, ['label' => 'Имя пользователя', 'attr' => ['placeholder' => 'Михаил']])
            ->add('password', PasswordType::class, ['label' => 'Пароль', 'attr' => ['placeholder' => 'Пароль']])
            ->add('repeatPassword', PasswordType::class, ['label' => 'Повторите пароль', 'attr' => ['placeholder' => 'Повторите пароль']])
            ->add('save', SubmitType::class, ['label' => 'Зарегистрироваться', 'attr' => ['class' => 'btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}