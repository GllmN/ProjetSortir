<?php

namespace App\Form;



use App\Entity\Event;
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
            ->add('campus', ChoiceType::class, [] )
            ->add('keyWord', TextType::class, [
                'label'=>'Le nom de la sortie contient :',
                'data' => 'Search'])
            ->add('dateStart', DateType::class, ['label'=>'Entre :'])
            ->add('dateEnd', DateType::class, ['label'=>'et :'])
            ->add('eventOrganizer', CheckboxType::class, ['label'=>'Sortie dont je suis l organisateur/trice :'])
            ->add('eventSuscriber', CheckboxType::class, ['label'=>'Sortie auquelles je suis inscrit/e :'])
            ->add('eventNotSuscriber', CheckboxType::class, ['label'=>'Sortie auquelles je ne suis pas inscrit/e :'])
            ->add('eventOld', CheckboxType::class, ['label'=>'Sortie passées :'])
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
