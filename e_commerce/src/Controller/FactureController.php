<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Service\GenPdf;
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
        EntityManagerInterface $em,
        Commande $commande,
        CommandeRepository $commandeRepository,

        ): Response  
    {

        $commande->getId();                         // Pour récupérer la commande 
        // dd($commande);

        $facture = new Facture();                   // Créer une facture
        $facture->setNumeroFacture(uniqid());       // Instencier le numéro de facture
        $facture->setCommande($commande);           // Lier la commande à la facture

        // dd($facture);

        $em->persist($facture);
        $em->flush();
        
        $this->addFlash(                            // Envoyer une notification
            'success',
            'Facture ajoutée avec succès!'
        );

        return $this->redirectToRoute('app_facture_detail_facture', ['id' => $commande->getId ()], Response::HTTP_SEE_OTHER); // Redirige vers le détail de la facture

    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR GENERER LE FICHIER PDF DE CHAQUE FACTURES

    #[Route('/pdf/{id}', name: 'facture_pdf')]
    public function generatePdfFacture(

        Facture $facture = null,
        GenPdf $pdf,
        Commande $commande,
        CommandeLivreRepository $commandeLivreRepository,
        CommandeRepository $commandeRepository

        )
    {

        $commandes = $commandeRepository->findBy([], ["numero_commande" => "ASC"]);
        $commandeLivres = $commandeLivreRepository->findAll();

        $html = $this->render('facture/detail.html.twig', [
            'facture' => $facture,
            'commande' => $commande,
            'commandeLivres' => $commandeLivres,
            'commandes' => $commandes

        ]);

        $pdf->showPdfFile($html);
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE DETAIL DE CHAQUE FACTURES

    #[Route('/facture/{id}/detail', name: 'detail_facture')]       // Reprendre la route en ajoutant /detail à l'URL et en changeant le nom du name
    public function detail(

        Commande $commande,
        CommandeLivreRepository $commandeLivreRepository,
        CommandeRepository $commandeRepository,
        

        ): Response
    {                                                            // Créer une fonction detail() dans le controller pour afficher le détail d'une facture 
        $commandes = $commandeRepository->findBy([], ["numero_commande" => "ASC"]);
        $commandeLivres = $commandeLivreRepository->findAll();
        
        return $this->render('facture/detail.html.twig', [          // Pour faire le lien entre le controller et la vue detail.html.twig (il faut donc la créer dans le dossier facture)
            'commande' => $commande,
            'commandeLivres' => $commandeLivres,
            'commandes' => $commandes,
            

        ]);
    }

}
