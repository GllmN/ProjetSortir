<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Bridge\Twig\Extension\twig_is_selected_choice;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo' , TextType::class,  ['label' => 'Pseudo :'])
            ->add('firstName',TextType::class,  ['label' => 'Prénom :'])
            ->add('lastName',TextType::class,  ['label' => 'Nom :'])
            ->add('phone',TextType::class,  ['label' => 'Téléphone :'])
            ->add('email',TextType::class,  ['label' => 'Email:'])
            ->add('password',RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' =>array('label' => 'Mot de passe :'),
                'second_options' =>array('label' =>'Confirmation :'),
            ))
            ->add('campus', EntityType::class, array(
                'class' => '',
                'property' => 'pseudo',
            ))
            // voir page symfony sur l'upload
            ->add('photo',TextType::class,  ['label' => 'Photo:'])
        ;
    }

}
