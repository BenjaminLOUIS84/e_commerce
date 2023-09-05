<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FactureController extends AbstractController
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LES COMMANDES DE CHAQUE FACTURES

    #[Route('/facture/{id}', name: 'app_facture')]

    public function index(Facture $facture, CommandeRepository $commandeRepository): Response
    
    {
        $commandes = $commandeRepository->findBy([], ["nom" => "ASC"]);

        return $this->render('facture/index.html.twig', [
            'facture' => $facture,
            'commandes' => $commandes
        ]);
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR METTRE LA COMMANDE EN FACTURE

    // #[Route('/ajout', name: 'add')]
    // public function add(): Response
    // {
    //     $commande = $session->get('commande', []);
    //     dd($commande); 
