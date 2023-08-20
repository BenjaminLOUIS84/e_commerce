<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
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

    
}
