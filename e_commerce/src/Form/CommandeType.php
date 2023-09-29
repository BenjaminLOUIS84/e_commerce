<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Commande;
use App\Entity\FormatLivre;
use App\Form\CommandeLivreType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('numero_commande')

            // ->add('user', EntityType::class, [
            //     'label' => 'Client',
            //     'class' => User::class, 
            //     'attr' => ['class' => 'form-control'],
            //     'choice_label' => 'pseudo'
            // ])

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

            // ->add('commandeLivres', CollectionType::class, [    // Particularité ici CoollectionType à besoin d'être paramétré pour fonctionner
            //     'entry_type' => CommandeLivreType::class,       // Pour ajouter un autre formulaire

            //     'prototype' => true,                            // Pour autoriser l'ajout de nouveaux éléments dans l'entité session qui seront persistés grâce aux cascade persist sur l'élément programme 
            //                                                     // Permet d'activer un data prototype qui sera un élément html qu'on pourra manipuler en JS
            //     'allow_add' => true,                            // Permet d'ajouter plusieurs éléments
            //     'allow_delete' => true,                         // Permet de supprimer plusieurs éléments   
            //     'by_reference' => false                         // OBLIGATOIRE Car Livre n'a pas de setFormatLivre, c'est FormatLivre qui contient setSession (Programme est propriétaire de la relation)
            // ])                                                  // Cela évite un mapping false

            ->add('Valider', SubmitType::class, [       // Ajouter directement le bouton submit ici
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
