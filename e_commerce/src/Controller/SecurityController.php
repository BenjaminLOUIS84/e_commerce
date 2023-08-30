<?php

namespace App\Controller;

use App\Form\ResetPasswordType;
use App\Service\SendMailService;
use App\Repository\UserRepository;
use App\Form\ResetPasswordRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

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
    public function forgottenPassword(                                      // Injecter les dépendances dont on a besoin dans la fonction et importer les class pour utiliser les variable $request et $user
        Request $request,
        UserRepository $userRepository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,

        //Créer un service pour envoyer des mail ou utiliser mailHog
        SendMailService $mail

    ): Response

    {                                                                       
        $form = $this->createForm(ResetPasswordRequestType::class);         // Récupérer le formulaire
        
        $form->handleRequest($request);                                     // Pour traiter le formulaire

        if($form->isSubmitted() && $form->isvalid()){                       // Pour vérifier si le formulaire est valide et soumis
            
            // On va chercher l'utilisateur par son email
            $user = $userRepository->findOneByEmail($form->get('email')->getData());    // Pour chercher les données dans l'email qui est inscrit dans le formulaire
        
            // On vérifie si on un utilisateur
            if($user){

                // On génère un token de réinitialisation
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);

                $entityManager->persist($user);                             // Pour gérer le traitement en BDD
                $entityManager->flush();                                    
            
                // On génère un lien de réinitialisation du mot de passe
                $url = $this->generateUrl('reset_pass', ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL);                       // Permet de générer l'url pour utiliser la nouvelle route pour créer un nouveau mot de passe

                // On créer les données du mail
                $context = compact('url', 'user');

                // Envoi du mail (Utiliser le service mail)
                $mail->send(
                    'etrefouetsage@gmail.com',                              // Emetteur
                    $user->getEmail(),                                      // Destinataire
                    'Réinitialisation de mot de passe',                     // Titre
                    'password_reset',                                       // Template 
                    $context
                );

                $this->addFlash('success', 'Email envoyé avec succès');
                return $this->redirectToRoute('app_login');                 // Redirection vers la page de connexion

            }
            // Cas où $user est NULL
            $this->addFlash('danger', 'Un problème est survenu');           // En cas d'erreur on est redirigé vers la page de connexion et le message s'affichera dans cette page (*)
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [ // Passer le formulaire en arguement dans un tableau
            
            'requestPassForm' => $form->createView()                        // Demande pour créer le formulaire 'requestPassFrom' et pour afficher selui-ci dans une vue
        ]);  
    }

    #[Route('/oubli-pass/{token}', name:'reset_pass')]                      // Cette nouvelle route permet d'afficher un autre formulaire pour créer un nouveau mot de passe
    public function resetPass(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // On vérifie si on a ce token dans la BDD
        $user = $userRepository->findOneByResetToken($token);
    
        if($user){

            $form = $this->createForm(ResetPasswordType::class);            // Créer le formulaire pour créer un nouveau mot de passe

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView()                           // Afficher ce formulaire dans la vue reset_password.html.twig 
            ]);
        }
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');
    }
}
