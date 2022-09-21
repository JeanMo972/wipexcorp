<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void //Création d'un formulaire
    {
        $builder
            ->add('firstname', TextareaType::class, [

                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre prénom '
                ]

            ]) // champs prénom

            ->add('lastname', TextareaType::class,[
            
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom '           
                ]
                    
            ]) // champs nom

            ->add('email', EmailType::class, [

                'label' => 'Votre email',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre adresse email '     
                ]
            ]) // champs email

            ->add('content', TextareaType::class, [

                'label' => 'Votre nessage',
                'attr' => [
                    'placeholder' => 'En quoi pouvons-nous vous aider ?'     
                ]

            ]) // champs message

            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => ['btn-block btn-success']
            
            ]) // champs soumettre
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configurer les option du formulaire ici
        ]);
    }
}
