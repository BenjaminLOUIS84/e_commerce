<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/panier', name: 'panier_')]
class PanierController extends AbstractController
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE PANIER

    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, LivreRepository $livreRepository)
    {
        $panier = $session->get('panier', []);
        // dd($panier);

        // Pour gérer les informations des livres dans le panier, il faut initialiser des variables
        $data = [];
        $total = 0;

        ////////////////////////////////////////////
        // ACTIVER pour effacer le panier manuellement
        // $session->set('panier', []);
        ////////////////////////////////////////////

        foreach($panier as $id => $quantity){       //  Faire une boucle pour réaliser l'action automatique pour chaque ajout de livres

            $livre = $livreRepository->find($id);   // Récupérer le livre suivant son id

            $data[] = [                             
                'livre' => $livre,                  // Ajouter dans un tableau le livre et ses informations
                'quantity' => $quantity             // Ajouter dans le tableau la quantité (à chaque click sur le bouton d'ajout)
            ];

            $total += $livre->getPrixUnitaire() * $quantity;
            // dd($data);
        }

        // $this->addFlash(                            // Envoyer une notification
        //     'success',
        //     'Livre ajouté avec succès!'
        // );

        return $this->render('panier/index.html.twig', compact('data', 'total'));

    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AJOUTER DES LIVRES DANS UN PANIER

    #[Route('/add/{id}', name: 'add')]
    public function add(Livre $livre, SessionInterface $session)
    {
        $id = $livre->getId();                          // Récupérer l'ID du livre
        $panier = $session->get('panier', []);          // Récupérer le panier existant, sinon récupérer un tableau vide
        
        // $panier[47] = 1;                             // [47] Correspond au livre Femmes et 1 représente la quantité
        // Représente l'action manuelle d'ajout de livre
        // Pour gérer cette action d'ajout de livre, créer la condition ci-dessous
        
        if(empty($panier[$id])){                        // Ajouter le livre dans le panier s'il n'y est pas encore, sinon incrémenter sa quantité
            $panier[$id] = 1;
        }else{
            $panier[$id]++;
        }

        $session->set('panier', $panier);               // Pour mettre le livre dans la session 
        // dd($session);                                // Pour dumper OU faire un varDump() de la variable $session et voir ce qu'il y a dedans
        return $this->redirectToRoute('panier_index');  // Redirection vers la page du panier
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR GERER LES ACTIONS

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Livre $livre, SessionInterface $session)
    {
        $id = $livre->getId();                          // Récupérer l'ID du livre
        $panier = $session->get('panier', []);          // Récupérer le panier existant, sinon récupérer un tableau vide
        
        if(!empty($panier[$id])){                       // Retirer le livre du panier s'il n'y a qu'un exemplaire, sinon décrémenter sa quantité
            
            if($panier[$id] > 1){                       // Si le panier contien plus d'un exemplaire alors décrémenter
                $panier[$id]--;
            }else{                                      // Sinon vider le panier
                unset($panier[$id]);
            }
        }

        $session->set('panier', $panier);               // Pour mettre le livre dans la session 
        return $this->redirectToRoute('panier_index');  // Redirection vers la page du panier
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Livre $livre, SessionInterface $session)
    {
        $id = $livre->getId();                          // Récupérer l'ID du livre
        $panier = $session->get('panier', []);          // Récupérer le panier existant, sinon récupérer un tableau vide
        
        if(!empty($panier[$id])){                       // Si le panier n'est pas vide alors                            
            unset($panier[$id]);                        // Vider le panier
        }

        $session->set('panier', $panier);               // Pour mettre le livre dans la session 
        return $this->redirectToRoute('panier_index');  // Redirection vers la page du panier
    }

    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session)
    {
        $session->remove('panier');                     // Supprimer tous les livres du panier dans la session
        return $this->redirectToRoute('panier_index');  // Redirection vers la page du panier
    }
}
