<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('picture', FileType::class, [                    // Champs pour ajouter un fichier (les images jpg uniquement)
                'mapped' => false,
                'label' => "Ajouter votre photo",
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

            ->add('contenu', TextareaType::class, [              // Champs pour les textes long, zone de texte pour les résumés
                'label' => 'Contenu',
                'attr' => ['class' => 'tinymce'],
                'required' => false                             // Pour rendre le résumé non obligatoire, rendre nullable la propriété resume dans la BDD
            ])
            
            ->add('dateArt', DateType::class, [                 // Ajouter après class ['widget' => 'single_text', 'attr' =>['class' =>'form-control']] Propiété BootStrap pour améliorer l'affichage de la date
                'label' => 'Date de publication',
                'widget' =>'single_text',
                'attr' =>['class' =>'form-control']
            ])

            // ->add('user', HiddenType::class)
            
            ->add('user', EntityType::class, [                 // Particularité ici EntityType à besoin d'un tableau d'arguments pour fonctionner
                'label' => 'Auteur',
                'class' => User::class, 
                'attr' => ['class' => 'form-control'],
                'choice_label' => 'pseudo'
            ])

            ->add('Enregistrer', SubmitType::class, [               // Ajouter directement le bouton submit ici
                'attr' =>['class' => 'btn btn-dark']
            ])                                                  // Ajouter après class ['attr' =>['class' =>'btn btn-dark']] Pour améliorer le bouton   
       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
