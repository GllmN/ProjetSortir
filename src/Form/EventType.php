<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eventName')
            ->add('dateAndHour')
            ->add('registrationLimit')
            ->add('numberOfPlaces')
            ->add('duration')
            ->add('description')
            ->add('location')
            ->add('street')
            ->add('postalCode')
            ->add('campus')
            ->add('city')
            ->add('save', SubmitType::class, ['label'=>'Enregistrer'])
            //->add('publish', SubmitType::class, ['label'=>'Publier la sortie'])
            //->add('cancel', SubmitType::class, ['label'=>'Annuler'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
