<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Commande;
use App\Repository\FactureRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/facture', name: 'app_facture_')]  
class FactureController extends AbstractController
{

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR METTRE LA COMMANDE EN FACTURE

    #[Route('/ajout/{id}', name: 'add')]
    public function add(

        SessionInterface $session,
        Facture $facture,
        FactureRepository $factureRepository,
        Commande $commande,
        CommandeRepository $commandeRepository,
        EntityManagerInterface $em
        
        ): Response  
    { 

        // $id = $commande->getId();
        // $commande = $commandeRepository->find($id);
        // $commande = $session->get('commande', []);
        // dd($commande);
        $id = $facture->getId();
        // $id = $facture->$factureRepository->find($id);
        dd($facture);

        // $data = [];


        $facture = new Facture();
        $facture->setCommande($this->getCommande());
        $facture->setNumeroFacture(uniqid());
        // dd($facture);

    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE DETAIL DE CHAQUE FACTURES

    #[Route('/facture/{id}/detail', name: 'detail_facture')]       // Reprendre la route en ajoutant /detail à l'URL et en changeant le nom du name
    public function detail(

        // Commande $commande,
        Facture $facture,
        // CommandeRepository $commandeRepository,
        FactureRepository $factureRepository,

        ): Response
    {                                                            // Créer une fonction detail() dans le controller pour afficher le détail d'une facture 
        // $commandes = $commandeRepository->findBy([], ["numero_commande" => "ASC"]);
        $facture = $factureRepository->findBy([], ["numero_facture" => "ASC"]);
        
        return $this->render('facture/detail.html.twig', [          // Pour faire le lien entre le controller et la vue detail.html.twig (il faut donc la créer dans le dossier facture)
            // 'commande' => $commande,
            'facture' => $facture,
            // 'commandes' => $commandes
            'factures' => $factures

        ]);
    }

    
}
