<?php

namespace App\Form;

use App\Entity\Commentaire;
use Doctrine\ORM\EntityRepository;
use App\Entity\Newsletters\Newsletters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
            // ->add('newsletters', EntityType::class, [                 // OBJ Séléctionner la newsletters à commenter automatiquement
            //     'label' => 'Sélectionnez la newsletter',
            //     'class' => Newsletters::class, 
            //     'attr' => ['class' => 'form-control'],
            //     'choice_label' => 'name'
            // ])
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
        //     ->add('newsletters', EntityType::class, [                 
        //         'label' => 'Sélectionnez la newsletter',
        //         'class' => Newsletters::class, 
        //         'attr' => ['class' => 'form-control'],
        //         'choice_label' => 'name',
        //         'query_builder' => function (EntityRepository $er) {
        //             return $er->createQueryBuilder('c')
        //                 ->where('c.id <> :newsletters_id')
        //                 ->setParameter('newsletters_id', $this->newsletters_id);
             
        //         }
        //    ])
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            
            ->add('content',TextareaType::class, [
                'label' => 'Votre commentaire',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('is_rgpd', CheckboxType::class, [
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter la collecte de vos 
                        données personnelles'
                    ])
                ],
                'label' => 'J\'accepte la collecte de mes données personnelles'
            ])

            ->add('parentid', HiddenType::class, [
                'mapped' => false
            ])

            ->add('envoyer', SubmitType::class, [
                'attr' =>['class' => 'btn btn-dark']
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
