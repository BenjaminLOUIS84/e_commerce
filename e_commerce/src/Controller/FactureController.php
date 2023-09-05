<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Repository\FactureRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FactureController extends AbstractController
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LA FACTURE DE CHAQUE COMMANDE

    #[Route('/facture/{id}', name: 'app_facture')]

    public function index(Facture $facture, CommandeRepository $commandeRepository): Response
    
    {
        $commandes = $commandeRepository->findBy([], ["nom" => "ASC"]);

        return $this->render('facture/index.html.twig', [
            'facture' => $facture,
            'commandes' => $commandes
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE DETAIL DE CHAQUE FACTURES

    #[Route('/facture/{id}/detail', name: 'detail_facture')]       // Reprendre la route en ajoutant /detail à l'URL et en changeant le nom du name

    public function detail(Facture $facture, FactureRepository $factureRepository): Response

    {                                                            // Créer une fonction detail() dans le controller pour afficher le détail d'une facture 
        $factures = $factureRepository->findBy([], ["numero_facture" => "ASC"]);
        
        return $this->render('facture/detail.html.twig', [          // Pour faire le lien entre le controller et la vue detail.html.twig (il faut donc la créer dans le dossier facture)
        
            'facture' => $facture,
            'factures' => $factures

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
