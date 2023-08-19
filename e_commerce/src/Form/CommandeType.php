<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('numero_commande')

            ->add('date_commande', DateType::class, [   // Ajouter après class ['widget' => 'single_text', 'attr' =>['class' =>'form-control']] Propiété BootStrap pour améliorer l'affichage de la date
                'label' => 'Date de commande',
                'widget' =>'single_text',
                'attr' =>['class' =>'form-control']
            ])

            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('adresse', TextType::class)
            ->add('cp', TextType::class)
            ->add('ville', TextType::class)

            ->add('Livre', EntityType::class, [         // Particularité ici EntityType à besoin d'un tableau d'arguments pour fonctionner
                'label' => 'Choisir un livre',
                'class' => Livre::class, 
                'attr' => ['class' => 'form-control'],
                'choice_label' => 'titre'
            ])

            ->add('valider', SubmitType::class, [       // Ajouter directement le bouton submit ici
            'attr' =>['class' => 'btn btn-dark']])      // Ajouter après class ['attr' =>['class' =>'btn btn-dark']] Pour améliorer le bouton   
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
