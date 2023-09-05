<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Commande;
use App\Repository\FactureRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/facture', name: 'app_facture_')]  
class FactureController extends AbstractController
{

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE DETAIL DE CHAQUE FACTURES

    #[Route('/facture/{id}/detail', name: 'detail_facture')]       // Reprendre la route en ajoutant /detail à l'URL et en changeant le nom du name
    public function detail(

        Commande $commande,
        CommandeRepository $commandeRepository,

    ): Response

    {                                                            // Créer une fonction detail() dans le controller pour afficher le détail d'une facture 
        $commandes = $commandeRepository->findBy([], ["numero_commande" => "ASC"]);
        
        return $this->render('facture/detail.html.twig', [          // Pour faire le lien entre le controller et la vue detail.html.twig (il faut donc la créer dans le dossier facture)
        
            'commande' => $commande,
            'commandes' => $commandes,

        ]);
    }

    
}
