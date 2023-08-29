<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController

{   // Fonction pour connecter un utilisateur
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    // Fonction pour déconnecter un utilisateur
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // Fonction pour reset le password
    #[Route(path: '/oubli-pass', name: 'forgotten_password')]
    public function forgottenPassword(): Response
    {
        $form = $this->createForm(ResetPasswordRequestType::class);         // Récupérer le formulaire
        
        return $this->render('security/reset_password_request.html.twig', [ // Passer le formulaire en arguement dans un tableau
            
            'requestPassForm' => $form->createView()                        // Demande pour créer le formulaire 'requestPassFrom' et pour afficher selui-ci dans une vue
        ]);  
    }
}
