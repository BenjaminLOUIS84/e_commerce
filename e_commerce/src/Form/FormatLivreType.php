<?php

namespace App\Form;

use App\Entity\Format;
use App\Entity\FormatLivre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class FormatLivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder////////////////////////////////////////////////////////////FORMULAIRE PARALELLE
           
            ->add('format', EntityType::class, [ 
                // 'multiple' => true, 
                // 'expanded' =>true,                   // Particularité ici EntityType à besoin d'un tableau d'arguments pour fonctionner
                'label' => 'Format',
                'class' => Format::class, 
                'attr' => ['class' => 'form-control'],
                'choice_label' => 'type'
            ])

            ->add('prix_unitaire', IntegerType::class, [
                'label' => 'Prix unitaire ttc',
                'attr' => ['min' => 1, 'max' => 20]
            ])
            
            ->add('livre', HiddenType::class)           // Pour cacher le champ
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FormatLivre::class,
        ]);
    }
}
