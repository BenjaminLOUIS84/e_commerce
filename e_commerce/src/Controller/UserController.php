<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Commande;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER L'ESPACE PERSONNEL

    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    { 
        $users = $userRepository->findBy([], ["Pseudo" => "ASC"]);      // Affiche tous les utilisateurs

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR SUPPRIMER UN COMPTE

    #[Route('/user/{id}/delete', name: 'delete_user')]                  // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name
    public function delete(User $user, EntityManagerInterface $entityManager): Response   
    {                                                                   // Créer une fonction delete() dans le controller pour supprimer un user            
        $entityManager->remove($user);                                  // Supprime un user
        $entityManager->flush();                                        // Exécute l'action DANS LA BDD

        $this->addFlash(                                                // Envoyer une notification
            'success',
            'Compte supprimé avec succès!'
        );

        return $this->redirectToRoute('app_home');                      // Rediriger vers la page d'accueil
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER COMMANDE DE CHAQUE UTILISATEURS

    #[Route('/user/{id}', name: 'show_user')]              // Reprendre la route en ajoutant /{id} à l'URL et en changeant le nom du name

    public function show(User $user): Response             // Créer une fonction show() dans le controller pour afficher le détail d'un user 

    {
        return $this->render('user/show.html.twig', [      // Pour faire le lien entre le controller et la vue show.html.twig (il faut donc la créer dans le dossier user)
            'user' => $user
        ]);
    }

}
