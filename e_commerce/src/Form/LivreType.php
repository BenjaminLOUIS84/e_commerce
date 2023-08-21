<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Serie;
use App\Entity\Format;
use App\Entity\Commande;
use App\Entity\FormatLivre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class)                 // Définir les types de champs et importer les classes

            ->add('date_publication', DateType::class, [    // Ajouter après class ['widget' => 'single_text', 'attr' =>['class' =>'form-control']] Propiété BootStrap pour améliorer l'affichage de la date
                'label' => 'Date de publication',
                'widget' =>'single_text',
                'attr' =>['class' =>'form-control']
            ])

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ->add('couverture', FileType::class, [          // Champs pour charger un fichier (image)                
                'mapped' => false,                          // Dissocier l'image de l'entité
                'required'=> false,                         // Rendre l'ajout d'image obligatoire
                'constraints' => [                          // Sécurité pour que le fichier soit une image au format jpg uniquement
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Image au format jpg uniquement',
                    ])
                ],
            ])                           
            
            ->add('tome', FileType::class, [
                'mapped' => false,
                'required'=> false,
                'constraints' => [                          
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Image au format jpg uniquement',
                    ])
                ],

            ])                  

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ->add('resume', TextareaType::class, [              // Champs pour les textes long, zone de texte pour les résumés
                'label' => 'Résumé',
                'attr' => ['class' => 'tinymce'],
                'required' => false                             // Pour rendre le résumé non obligatoire, rendre nullable la propriété resume dans la BDD
            ])

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // ->add('commandes', EntityType::class, [         // Particularité ici EntityType à besoin d'un tableau d'arguments pour fonctionner
            // 'mapped' => false,                              // Pour permettre l'affichage de ce champs dans le formulaire mettre le mappage en false
            // 'class' => Commande::class,                     // PAS BESOIN POUR LE MOMENT
            // 'attr' => ['class' => 'form-control'],
            // 'choice_label' => 'numero_commande'])
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            ->add('formatLivres', EntityType::class, [          // Particularité ici EntityType à besoin d'un tableau d'arguments pour fonctionner
                'multiple' => true, 
                'expanded' =>true,                            // Autorise l'affichage d'un champ multiple dans le cas d'une collection
                'label' => 'Formats',
                'class' => Format::class, 
                'attr' => ['class' => 'check-box'],
                'choice_label' => 'type'
            ])

            // ->add('formatLivres', EntityType::class, [          // Particularité ici EntityType à besoin d'un tableau d'arguments pour fonctionner
            //     'label' => 'Prix TCC',
            //     'class' => Format::class, 
            //     'attr' => ['class' => 'form-control'],
            //     'choice_label' => 'prix_unitaire'
            // ])

            ->add('serie', EntityType::class, [                 // Particularité ici EntityType à besoin d'un tableau d'arguments pour fonctionner
                'label' => 'Collection',
                'class' => Serie::class, 
                'attr' => ['class' => 'form-control'],
                'choice_label' => 'intitule'
            ])

            ->add('valider', SubmitType::class, [               // Ajouter directement le bouton submit ici
                'attr' =>['class' => 'btn btn-dark']
            ])                                                  // Ajouter après class ['attr' =>['class' =>'btn btn-dark']] Pour améliorer le bouton   
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);

        
    }
}
