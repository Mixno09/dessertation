<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportFlightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numberAirplane', TextType::class, ['label' => 'Номер борта'])
            ->add('date', DateType::class, ['label' => 'Дата вылета'])
            ->add('numberFlight', TextType::class, ['label' => 'Номер вылета'])
            ->add('flightInformation', FileType::class, ['label' => 'Выберите файл'])
            ->add('save', SubmitType::class, ['label' => 'Загрузить данные', 'attr' => ['class' => 'btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}