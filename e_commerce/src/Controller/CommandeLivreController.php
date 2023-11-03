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
    public function index(CommandeLivre $commandeLivre, CommandeLivreRepository $commandeLivreRepository): Response
    {
       
        $commandeLivres = $commandeLivreRepository->findAll();
        
        return $this->render('commande_livre/index.html.twig', [
            
            'commandeLivre' => $commandeLivre,
            'commandeLivres' => $commandeLivres

        ]);
    }
}
