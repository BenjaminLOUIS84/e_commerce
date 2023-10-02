<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaire', TextareaType::class , [            // Champs pour les textes long, zone de texte pour les résumés
                'label' => 'Contenu',
                'attr' => ['class' => 'tinymce'],
                'required' => false                                 // Pour rendre le résumé non obligatoire, rendre nullable la propriété resume dans la BDD
            ])
            // ->add('dateCom')
            // ->add('user')
            // ->add('newsletters')
            ->add('Envoyer', SubmitType::class, [                   // Ajouter directement le bouton submit ici
                'attr' =>['class' => 'btn btn-dark']
            ])                                                      // Ajouter après class ['attr' =>['class' =>'btn btn-dark']] Pour améliorer le bouton   
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
