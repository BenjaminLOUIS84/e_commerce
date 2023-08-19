<?php

namespace App\Controller;

use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(): Response
    {
        
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION FORMULAIRE POUR AJOUTER UN LIVRE DANS LA COMMANDE

    #[Route('/livre/{id}/addLivre', name: 'add_livre')]             // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name

    public function addLivre(Livre $livre, 
    //Request $request, 
    EntityManagerInterface $entityManager): Response                // Créer une fonction addLivre() dans le controller pour permettre l'ajout d'un livre

    {
        ////////////////////////////////////////////////////////////GERER LE TRAITEMENT EN BDD
        
        // $commande->handleRequest($request);
        
        // $livre->getData();                          // Récupérer les informations du livre 
        //prepare PDO
        $entityManager->persist($livre);                        // Dire à Doctrine que je veux sauvegarder le livre          
        //execute PDO
        $entityManager->flush();                                // Mettre le livre dans la BDD

        $this->addFlash(                                        // Envoyer une notification
            'success',
            'Ajouté avec succès!'
        );

        return $this->redirectToRoute('app_commande');          // Rediriger vers le panier
    
        ////////////////////////////////////////////////////////////

        return $this->render('commande/index.html.twig', [      // Pour faire le lien entre le controller et la vue index.html.twig du dossier commande
            'addLivre' => $livre->getId()
        ]);
    }

}
