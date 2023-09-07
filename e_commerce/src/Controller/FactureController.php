<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Commande;
use App\Entity\CommandeLivre;
use App\Repository\FactureRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandeLivreRepository;
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
        // Facture $facture,
        // FactureRepository $factureRepository,
        Commande $commande,
        // CommandeLivre $commandeLivres,
        // CommandeLivreRepository $commandeLivreRepository,
        CommandeRepository $commandeRepository,
        EntityManagerInterface $em
        
        ): Response  
    { 
        // $session->getCommande();

        $id = $commande->getId();
        // $id = $commandeLivres->getId();
        $commande = $commandeRepository->find($id);
        // $commande = setCommande($this->getCommandeLivres());
        // dd($commande);

        // $id = $facture->getId();
        // $id = $facture->$factureRepository->find($id);
        // dd($facture);

        $facture = new Facture();
        // $facture->setCommande($this->getCommande());
        $facture->setNumeroFacture(uniqid());
        // $facture->setDateFacture($this->getDateFacture());

        // dd($facture);

        $em->persist($facture);
        $em->flush();
        
        $this->addFlash(                                                // Envoyer une notification
            'success',
            'Facture ajoutée avec succès!'
        );

        return $this->redirectToRoute('app_facture_detail_facture', ['id' => $commande->getId ()], Response::HTTP_SEE_OTHER); // Redirige vers le détail de la facture

    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE DETAIL DE CHAQUE FACTURES

    #[Route('/facture/{id}/detail', name: 'detail_facture')]       // Reprendre la route en ajoutant /detail à l'URL et en changeant le nom du name
    public function detail(

        Commande $commande,
        // Facture $facture,
        CommandeLivreRepository $commandeLivreRepository,
        CommandeRepository $commandeRepository,
        // FactureRepository $factureRepository,

        ): Response
    {                                                            // Créer une fonction detail() dans le controller pour afficher le détail d'une facture 
        $commandes = $commandeRepository->findBy([], ["numero_commande" => "ASC"]);
        $commandeLivres = $commandeLivreRepository->findAll();
        
        return $this->render('facture/detail.html.twig', [          // Pour faire le lien entre le controller et la vue detail.html.twig (il faut donc la créer dans le dossier facture)
            'commande' => $commande,
            'commandeLivres' => $commandeLivres,
            // 'facture' => $facture,
            'commandes' => $commandes,
            // 'factures' => $factures

        ]);
    }

    
}
