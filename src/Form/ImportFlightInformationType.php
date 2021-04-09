<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportFlightInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('airplaneNumber', IntegerType::class, ['label' => 'Номер борта'])
            ->add('flightDate', DateType::class, ['label' => 'Дата вылета', 'input' => 'datetime_immutable'])
            ->add('flightNumber', IntegerType::class, ['label' => 'Номер вылета'])
            ->add('file', FileType::class, ['label' => 'Выберите файл'])
            ->add('save', SubmitType::class, ['label' => 'Загрузить данные', 'attr' => ['class' => 'btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ImportFlightInformationDto::class,
        ]);
    }
}
