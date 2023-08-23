<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\LivreRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandeLivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

 class CommandeController extends AbstractController
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION ADMINISTRATEUR POUR AFFICHER TOUTES LES COMMANDES

    #[Route('/commande', name: 'app_commande')]
    public function index(CommandeRepository $commandeRepository): Response

    {                                                               
        
        $commandes = $commandeRepository->findBy([], ["date_commande" => "ASC"]);


        //$commandeLivre = $commandeLivreRepository->find($id)

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
            // 'commandeLivres' => $commandeLivres,
            // 'livres' => $livres,

            // 'commandeLivre => commandeLivre
        ]);
    }

    
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

        return $this->redirectToRoute('app_commande');                     // Rediriger vers la liste des commandes
       
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

        //////////////////////////////////////////////////////////////////////////
        //                                                      GERER LE TRAITEMENT EN BDD
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

            return $this->redirectToRoute('show_commande');         // Rediriger vers la liste des commandes
        }

        //////////////////////////////////////////////////////////////////////////


        return $this->render('commande/new.html.twig', [           // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier commande)
            'form' => $form,
            'edit' => $commande->getId(),
            'commandeId' => $commande->getId()
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION UTILISATEUR POUR AFFICHER LE DETAIL DE CHAQUE COMMANDE

    #[Route('/commande/{id}', name: 'show_commande')]              // Reprendre la route en ajoutant /{id} à l'URL et en changeant le nom du name

    public function show(Commande $commande, 
    LivreRepository $livreRepository

    ): Response             // Créer une fonction show() dans le controller pour afficher le détail d'une commande 

    {
        $livres = $livreRepository->findAll();

        return $this->render('commande/show.html.twig', [          // Pour faire le lien entre le controller et la vue show.html.twig (il faut donc la créer dans le dossier commande)
            'commande' => $commande,
            'livres' => $livres
            
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION ADMINISTRATEUR POUR AFFICHER LE DETAIL DE CHAQUE COMMANDE
    
    #[Route('/commande/{id}', name: 'see_commande')]              // Reprendre la route en ajoutant /{id} à l'URL et en changeant le nom du name
    
    #[IsGranted('ROLE_ADMIN')]                                    // Pour que l'administrateur puisse accéder à une vue différente du détail des commandes
    
    public function see(Commande $commande): Response             // Créer une fonction see() dans le controller pour afficher le détail d'une commande 

    {
        return $this->render('commande/see.html.twig', [          // Pour faire le lien entre le controller et la vue see.html.twig (il faut donc la créer dans le dossier commande)
            'commande' => $commande
        ]);
    }

    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION PANIER POUR AJOUTER UN LIVRE DANS LA COMMANDE

    #[Route('/livre/{id}/addLivre', name: 'add_livre')]             // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name

    public function addLivre(Livre $livre, EntityManagerInterface $entityManager): Response                // Créer une fonction addLivre() dans le controller pour permettre l'ajout d'un livre

    {
        ////////////////////////////////////////////////////////////GERER LE TRAITEMENT EN BDD
        
        // $livre = new Livre();
        
        // $livre->getData();                          // Récupérer les informations du livre 
        //prepare PDO
        $entityManager->persist($livre);                        // Dire à Doctrine que je veux sauvegarder le livre          
        //execute PDO
        $entityManager->flush();                                // Mettre le livre dans la BDD

        // $this->addFlash(                                        // Envoyer une notification
        //     'success',
        //     'Ajouté avec succès!'
        // );

        return $this->redirectToRoute('app_commande');          // Rediriger vers le panier
    
        ////////////////////////////////////////////////////////////

        return $this->render('commande/index.html.twig', [      // Pour faire le lien entre le controller et la vue index.html.twig du dossier commande
            'addLivre' => $livre->getId()
        ]);
    }

}
