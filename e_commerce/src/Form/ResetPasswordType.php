<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                    
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer'],
            
                'constraints' => [                                                      
                    new Length([                                // Pour faire en sorte que le password contienne minimum 12 caractères
                        
                        'min' => 12,
                        'minMessage' => 'Votre mot de passe doit contenir au moins 12 caractères',
                        
                        'max' => 4096                           // Nombre maximum de caractères autorisé par Symfony
                    ]),
                    
                    new Regex([                                 // Pour faire en sorte que le password contienne minimum une majuscule,...
                        
                        'pattern' => "/^\S*(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=\S*[\W])[a-zA-Z\d]{12,}\S*$/",
                        'message' => 'Minimum 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial',
                    ]),

                ]

            ])
                
            ->add('valider', SubmitType::class, [       // Ajouter directement le bouton submit ici
                'attr' =>['class' => 'btn btn-dark']
            ])                                          // Ajouter après class ['attr' =>['class' =>'btn btn-dark']] Pour améliorer le bouton
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
