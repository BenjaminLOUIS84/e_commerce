<?php

namespace App\Form;

use App\Entity\Newsletters\Users;
use App\Entity\Newsletters\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class NewslettersUsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' =>['class' =>'form-control']
            ])

            ->add('categories', EntityType::class,[
                'class' => Categories::class,
                'label' => 'Choisissez une ou plusieurs catégories',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'attr' =>['class' =>'form-control']
            ])

            ->add('is_rgpd', CheckboxType::class, [
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter la collecte de vos 
                        données personnelles'
                    ])
                ],
                'label' => 'J\'ai lu et j\'accepte les CGV'
            ])
            ->add('Enregistrer', SubmitType::class, [               // Ajouter directement le bouton submit ici
                'attr' =>['class' => 'btn btn-dark']
            ])                                  
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
