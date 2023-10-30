<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Facture;
use App\Entity\Commande;
use App\Entity\CommandeLivre;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\UserRepository;
use App\Repository\FactureRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandeLivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER L'ESPACE PERSONNEL

    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository)
    { 
        $users = $userRepository->findBy([], ["Pseudo" => "ASC"]);      // Affiche tous les utilisateurs

        return $this->render('user/index.html.twig', compact('users'));
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR SUPPRIMER UN COMPTE

    #[Route('/user/{id}/delete', name: 'delete_user')]
    public function delete(User $user, EntityManagerInterface $entityManager): Response   
    {                                                                   // Créer une fonction delete() dans le controller pour supprimer un user            
        
        if ($this->getUser() != $user) {                                // Permet d'empécher l'accès à cette action si l'id dans l'URL ne correspond pas à celui de l'utilisateur
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        $entityManager->remove($user);                                  // Supprime un user
        $entityManager->flush();                                        // Exécute l'action DANS LA BDD

        $this->addFlash(                                                // Envoyer une notification
            'success',
            'Compte supprimé avec succès!'
        );

        return $this->redirectToRoute('app_home');                      // Rediriger vers la page d'accueil
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LES COMMANDES ET LES FACTURES DE CHAQUE UTILISATEURS

    #[Route('/user/{id}', name: 'show_user')]                               // Reprendre la route en ajoutant /{id} à l'URL et en changeant le nom du name
    // #[Route('/user/{slug}-{id<[0-9]+>}', name: 'show_user', requirements: ['slug' => '[a-z0-9\-]*'])]                               // Reprendre la route en ajoutant /{id} à l'URL et en changeant le nom du name

    public function show(
        User $user,
        FactureRepository $factureRepository,
        CommandeRepository $commandeRepository,
        CommandeLivreRepository $commandeLivreRepository,
        // string $slug,
        ): Response                                                         // Créer une fonction show() dans le controller pour afficher le détail d'un user 

    {

        if($this->getUser() != $user){                                      // Si l'id de l'utilisateur dans l'url ne correspond pas à l'utilisateur connecté
            throw $this->createNotFoundException('Page non trouvée');
        }  
        
        // if($user->getSlug() !== $slug){
        //     return $this->redirectToRoute('show_user', [
        //         'id' =>$user->getId(),
        //         'slug' => $user->getSlug(),
        //     ], 301);
        // }
        
        $commandes = $commandeRepository->findBy([], ["nom" => "ASC"]);     // Affiche tous les commandes de l'utilisateur connecté
        $commandeLivres = $commandeLivreRepository->findAll();     
        $factures = $factureRepository->findAll();      

        return $this->render('user/show.html.twig', [                       // Pour faire le lien entre le controller et la vue show.html.twig (il faut donc la créer dans le dossier user)
            'user' => $user,
            'commandes' => $commandes,
            'commandeLivres' => $commandeLivres,
            'factures' => $factures
        ]);
        
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE DETAIL DE CHAQUE COMMANDES

    // #[Route('/user/{id}/detail', name: 'detail_commande')]       // Reprendre la route en ajoutant /detail à l'URL et en changeant le nom du name
    #[Route('/user/{slug}-{id<[0-9]+>}/detail', name: 'detail_commande', requirements: ['slug' => '[a-z0-9\-]*'])]       // Reprendre la route en ajoutant /detail à l'URL et en changeant le nom du name
    public function detail(
       
        Commande $commande,
        CommandeLivreRepository $commandeLivreRepository,
        string $slug
    
    ): Response

    {                                                            // Créer une fonction detail() dans le controller pour afficher le détail d'une commande 
        
        if($commande->getSlug() !== $slug){
                return $this->redirectToRoute('detail_commande', [
                    'id' =>$commande->getId(),
                    'slug' => $commande->getSlug(),
                ], 301);
            }

        // $commandeLivres = $commandeLivreRepository->findBy(["commande" => 78], ["livre" => "ASC"]);
        $commandeLivres = $commandeLivreRepository->findBy([], ["livre" => "ASC"]);
       
        return $this->render('user/detail.html.twig', [          // Pour faire le lien entre le controller et la vue detail.html.twig (il faut donc la créer dans le dossier commande)
        
            'commande' => $commande,
            'commandeLivres' => $commandeLivres
        ]);
    }

}
