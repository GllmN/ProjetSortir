<?php

namespace App\Form;

use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo' , TextType::class,  ['label' => 'Pseudo :', 'required' => true])
            ->add('firstName',TextType::class,  ['label' => 'Prénom :', 'required' => true])
            ->add('lastName',TextType::class,  ['label' => 'Nom :', 'required' => true])
            ->add('phone',TextType::class,  ['label' => 'Téléphone :', 'required' => true])
            ->add('email',TextType::class,  ['label' => 'Email:', 'required' => true])
            ->add('password',RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne sont pas identiques',
                'constraints'=> [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]), new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ])],
                'first_options' =>['label' => 'Mot de passe :'],
                'second_options' =>['label' =>'Confirmation :'],
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'required' => true
            ])
            // voir page symfony sur l'upload
            ->add('photo',TextType::class,  ['label' => 'Photo:'])
            ->add('abort', SubmitType::class, ['label'=>'Annuler'])
        ;
    }

}
