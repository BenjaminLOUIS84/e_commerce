<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeLivreController extends AbstractController
{
    #[Route('/commande/livre', name: 'app_commande_livre')]
    public function index(): Response
    {
        return $this->render('commande_livre/index.html.twig', [
            'controller_name' => 'CommandeLivreController',
        ]);
    }
}
