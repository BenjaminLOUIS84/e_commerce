<?php

namespace App\Form;

use App\Entity\Format;
use App\Entity\FormatLivre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class FormatLivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix_unitaire', IntegerType::class)

            ->add('format', EntityType::class, [                 // Particularité ici EntityType à besoin d'un tableau d'arguments pour fonctionner
                'label' => 'Formats',
                'class' => Format::class, 
                'attr' => ['class' => 'form-control'],
                'choice_label' => 'type'
            ])
            
            //->add('livre', CollectionType::class, [

            //])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FormatLivre::class,
        ]);
    }
}
