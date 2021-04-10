<?php

namespace App\Form;



use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', ChoiceType::class, [] )
            ->add('keyWord', TextType::class, [] )
            ->add('dateStart', DateType::class, [])
            ->add('dateEnd', DateType::class, [])
            ->add('eventOrganizer', ChoiceType::class, [])
            ->add('eventSuscriber', ChoiceType::class, [])
            ->add('eventNotSuscriber', ChoiceType::class, [])
            ->add('eventOld', ChoiceType::class, [])
            ->add('search', SubmitType::class, ['label'=>'Rechercher'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
