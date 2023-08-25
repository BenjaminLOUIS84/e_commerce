<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Entity\CommandeLivre;
use App\Repository\UserRepository;
use App\Repository\LivreRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandeLivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commande', name: 'app_commande_')]
class CommandeController extends AbstractController
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR METTRE LE PANIER EN COMMANDE

    #[Route('/ajout', name: 'add')]
    public function add(SessionInterface $session): Response   // Récupérer la session du panier
    {    
        // $this->denyAccesUnlessGranted('ROLE_USER');         // Permet de vérifier si un user est connecté pour passer une commande
       
        $panier = $session->get('panier', []);                 // Récupérer le panier ou un tableau vide
        // dd($panier);                                        // Vérifier si le panier est bien récupéré

        if($panier === []){                                    // Si le panier est vide alors
            $this->addFlash(                                   // Envoyer une notification
                'notice',
                'Votre panier est vide'
            );
            return $this->redirectToRoute('app_livre');        // Rediriger vers la liste des livres
        }

        $commande = new Commande();                            // Si le panier n'est pas vide, alors créer la commande

        // Parcourir le panier pour créer les détails de la commande

        foreach ($panier as $item => $quantity) {
            $commandeLivre = new CommandeLivre();
        }

        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController'
        ]);
        
       
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION ADMINISTRATEUR POUR AFFICHER TOUTES LES COMMANDES

    // #[Route('/commande', name: 'app_commande')]
    // public function index(CommandeRepository $commandeRepository): Response

    // {                                                               
        
        // $commandes = $commandeRepository->findBy([], ["date_commande" => "ASC"]);


        //$commandeLivre = $commandeLivreRepository->find($id)

        // return $this->render('commande/index.html.twig', [
            // 'commandes' => $commandes,
            // 'commandeLivres' => $commandeLivres,
            // 'livres' => $livres,

            // 'commandeLivre => commandeLivre
        // ]);
    // }

    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION UTILISATEUR POUR SUPPRIMER UNE COMMANDE

    #[Route('/commande/{id}/delete', name: 'delete_commande')]          // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name
    public function delete(Commande $commande, EntityManagerInterface $entityManager): Response   
    {                                                                   // Créer une fonction delete() dans le controller pour supprimer une commande            
        $entityManager->remove($commande);                              // Supprime une commande
        $entityManager->flush();                                        // Exécute l'action DANS LA BDD

        $this->addFlash(                                                // Envoyer une notification
            'success',
            'Commande supprimée avec succès!'
        );

        return $this->redirectToRoute('app_user');                     // Rediriger vers la liste des commandes
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION UTILISATEUR FORMULAIRE POUR AJOUTER et EDITER DES COMMANDES

    #[Route('/commande/new', name: 'new_commande')]                   // Reprendre la route en ajoutant /new à l'URL et en changeant le nom du name
    #[Route('/commande/{id}/edit', name: 'edit_commande')]            // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name
    public function new_edit(Commande $commande  = null, Request $request, EntityManagerInterface $entityManager): Response   
    {
        if(!$commande){                                               // S'il n'ya pas de commande à modifier alors en créer une nouvelle
            $commande = new Commande();                               // Après avoir importé la classe Request Déclarer une nouvelle commande
        }

        $form = $this->createForm(CommandeType :: class, $commande);  // Créer un nouveau formulaire avec la méthode createForm() et importer le classe CommandeType

        ///////////////////////////////////////////////////////////// GERER LE TRAITEMENT EN BDD
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {            // Si le formulaire soumis est valide alors
            
            $commande = $form->getData();                          // Récupérer les informations de la nouvelle commande 
            
            //prepare PDO
            $entityManager->persist($commande);                    // Dire à Doctrine que je veux sauvegarder la nouvelle commande           
            //execute PDO
            $entityManager->flush();                               // Mettre la nouvelle commande dans la BDD

            $this->addFlash(                                       // Envoyer une notification
                'success',
                'Opération réalisée avec succès!'
            );

            return $this->redirectToRoute('app_user');              // Rediriger vers la liste des commandes
        }
        
        return $this->render('commande/new.html.twig', [            // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier commande)
            'form' => $form,
            'edit' => $commande->getId(),
            'commandeId' => $commande->getId()
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION UTILISATEUR POUR AFFICHER LE DETAIL DE CHAQUE COMMANDE

    #[Route('/commande/{id}', name: 'show_commande')]              // Reprendre la route en ajoutant /{id} à l'URL et en changeant le nom du name
    public function show(Commande $commande, CommandeLivreRepository $commandeLivreRepository): Response             // Créer une fonction show() dans le controller pour afficher le détail d'une commande 
    {
        $commandeLivres = $commandeLivreRepository->findAll();
       
        return $this->render('commande/show.html.twig', [          // Pour faire le lien entre le controller et la vue show.html.twig (il faut donc la créer dans le dossier commande)
            'commande' => $commande,
            'commandeLivres' => $commandeLivres,
        ]);
    }
}
