<?php

namespace App\Form;

use App\Entity\Newsletters\Categories;
use App\Entity\Newsletters\Newsletters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NewslettersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('categories', EntityType::class, [                 // Particularité ici EntityType à besoin d'un tableau d'arguments pour fonctionner
                'label' => 'Catégories',
                'class' => Categories::class, 
                'attr' => ['class' => 'form-control'],
                'choice_label' => 'name'
            ])

            ->add('name', TextType::class)

            ->add('picture', FileType::class, [                    // Champs pour ajouter un fichier (les images jpg uniquement)
                'mapped' => false,
                'label' => "Ajouter une image",
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

            ->add('content', TextareaType::class , [              // Champs pour les textes long, zone de texte pour les résumés
                'label' => 'Rédiger du contenu',
                'attr' => ['class' => 'tinymce'],
                'required' => false                             // Pour rendre le résumé non obligatoire, rendre nullable la propriété resume dans la BDD
            ])

            ->add('valider', SubmitType::class, [               // Ajouter directement le bouton submit ici
                'attr' =>['class' => 'btn btn-dark']
            ])                                                  // Ajouter après class ['attr' =>['class' =>'btn btn-dark']] Pour améliorer le bouton   
       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Newsletters::class,
        ]);
    }
}
