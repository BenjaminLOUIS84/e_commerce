<?php

namespace App\Form;

use App\Entity\Format;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FormatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', TextType::class)                  // Définir les types de champs et importer les classes
        
        ->add('description', TextareaType::class, [     // Champs pour les textes long, zone de texte pour les descriptions
            'label' => 'Description',
            'attr' => ['class' => 'tinymce'],
            'attr' =>['class' =>'form-control']
            
        ])
        
        ->add('photo', FileType::class, [               // Champs pour charger un fichier (image)                
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

        ->add('Valider', SubmitType::class, [   // Ajouter directement le bouton submit ici
        'attr' =>['class' => 'btn btn-dark']])  // Ajouter après class ['attr' =>['class' =>'btn btn-dark']] Pour améliorer le bouton   
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Format::class,
        ]);
    }
}
