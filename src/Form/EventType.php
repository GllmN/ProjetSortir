<?php

namespace App\Form;

use App\Entity\Cities;
use App\Entity\Event;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eventName', TextType::class,['label'=>'Nom de la sortie'])
            ->add('dateAndHour',DateTimeType::class,[
                'label'=>'Date et heure de la sortie',
                'date_format'=>'ddMMyyyy',
            ])
            ->add('registrationLimit',DateType::class,[
                'label'=>'Date limite d\'inscription',
                'format'=>"ddMMyyyy",
            ])

            ->add('numberOfPlaces',null,['label'=>'Nombre de place'])
            ->add('duration',null,['label'=>'Durée'])
            ->add('description',null,['label'=>'Description et infos'])
            //->add('campus',null,['label'=>'Campus'])
            ->add('city',null,['label'=>'Ville'])
            ->add('location',null,['label'=>'Lieu'])

            ->add('save', SubmitType::class, ['label'=>'Enregistrer'])
            ->add('publish', SubmitType::class, ['label'=>'Publier la sortie'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
