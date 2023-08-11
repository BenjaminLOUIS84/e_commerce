<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Format;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', TextType::class)          // Définir les types de champs et importer les classes
        ->add('Livre', EntityType::class,  [    // Particlarité ici EntityType à besoin d'un tableau d'arguments pour fonctionner
        'class' => Livre::class, 
        'attr' => ['class' => 'form-control'],
        'choice_label' => 'titre'])

        ->add('valider', SubmitType::class, [   // Ajouter directement le bouton submit ici
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
