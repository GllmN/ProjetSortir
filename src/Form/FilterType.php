<?php

namespace App\Form;



use App\Entity\Campus;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('campus', EntityType::class, [
                'class'=> Campus::class,
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('keyWord', TextType::class, [
                'label'=>'Le nom de la sortie contient :',
                'data' => 'Mot clé',
                'required'=>false])
            ->add('dateStart', DateType::class, ['label'=>'Entre :'])
            ->add('dateEnd', DateType::class, ['label'=>'et :'])
            ->add('eventOrganizer', CheckboxType::class, [
                'label'=>'Sortie dont je suis l\'organisateur(trice).',
                'required'=>false,
            ])
            ->add('eventSuscriber', CheckboxType::class, [
                'label'=>'Sortie auquelles je suis inscrit(e).',
                'required'=>false,
            ])
            ->add('eventNotSuscriber', CheckboxType::class, [
                'label'=>'Sortie auquelles je ne suis pas inscrit(e).',
                'required'=>false,
            ])
            ->add('eventOld', CheckboxType::class, [
                'label'=>'Sortie passées.',
                'required'=>false,
            ])
            ->add('search', SubmitType::class, ['label'=>'Rechercher'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Suppression de l'association avec l'event
            // car par d'entity de ce formulaire dans l'entité Event
            //'data_class' => Event::class,
        ]);
    }
}
