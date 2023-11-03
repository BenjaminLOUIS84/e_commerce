<?php

namespace App\Controller;

use App\Entity\CommandeLivre;
use App\Repository\CommandeLivreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeLivreController extends AbstractController
{
    #[Route('/commande/livre', name: 'app_commande_livre')]
    public function index(
        // CommandeLivre $commandeLivre, 
        CommandeLivreRepository $commandeLivreRepository
        
    ): Response

    {
        if (!$this->isGranted('ROLE_ADMIN')) {                              // Permet d'empécher l'accès à cette action si ce n'est pas un admin
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        $commandeLivres = $commandeLivreRepository->findAll();

        return $this->render('commande_livre/index.html.twig', [
            
            // 'commandeLivre' => $commandeLivre,
            'commandeLivres' => $commandeLivres

        ]);
    }
}
