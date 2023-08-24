<?php

namespace App\Controller;

// use App\Entity\FormatLivre;
use App\Entity\Livre;
use App\Entity\FormatLivre;
use App\Repository\FormatLivreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormatLivreController extends AbstractController
{
    #[Route('/formatLivre', name: 'app_formatLivre')]
    
    public function index(FormatLivreRepository $formatLivreRepository, FormatLivre $formatLivre): Response
    {
        $formatLivres = $formatLivreRepository->findAll();

        return $this->render('format_livre/index.html.twig', [

            'formatLivres' => $formatLivres,
            
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE DETAIL DE CHAQUE LIVRES

    #[Route('/formatLivres/{id}', name: 'show_format_Livre')]                 // Reprendre la route en ajoutant /{id} à l'URL et en changeant le nom du name

    public function show(FormatLivre $formatLivre, Livre $livre
    

    ): Response   // Créer une fonction show() dans le controller pour afficher le détail d'un formatLivre 

    {
        return $this->render('formatLivres/show.html.twig', [          // Pour faire le lien entre le controller et la vue show.html.twig (il faut donc la créer dans le dossier formatLivre)
            'formatLivre' => $formatLivre,
            'livre' => $livre
        ]);
    }

}
