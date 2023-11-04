<?php

namespace App\Controller;

use App\Repository\CommandeLivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeLivreController extends AbstractController
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LA LISTE DU TOUTES DES COMMANDES

    #[Route('/commande/livre', name: 'app_commande_livre')]
    public function index(CommandeLivreRepository $commandeLivreRepository): Response

    {
        if (!$this->isGranted('ROLE_ADMIN')) {                              // Permet d'empécher l'accès à cette action si ce n'est pas un admin
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        $commandeLivres = $commandeLivreRepository->findBy([], ["id" => "DESC"]); // Classer les commandes de la plus récente à la plus ancienne

        return $this->render('commande_livre/index.html.twig', [
            
            'commandeLivres' => $commandeLivres,
            
        ]);
    }
}
