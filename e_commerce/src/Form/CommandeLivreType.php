<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\CommandeLivre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CommandeLivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('commande', HiddenType::class)

            ->add('livre', EntityType::class, [ 
                // 'multiple' => true, 
                // 'expanded' =>true,                   // Particularité ici EntityType à besoin d'un tableau d'arguments pour fonctionner
                'label' => 'Livre',
                'class' => Livre::class, 
                'attr' => ['class' => 'form-control'],
                'choice_label' => 'titre'
            ])

            ->add('prix_unitaire' , IntegerType::class, [
                'label' => 'Quantité',
                'attr' => ['min' => 1, 'max' => 90]
            ])

            ->add('prix_unitaire' , IntegerType::class, [
                'label' => 'Prix',
                'attr' => ['min' => 1, 'max' => 90]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommandeLivre::class,
        ]);
    }
}
