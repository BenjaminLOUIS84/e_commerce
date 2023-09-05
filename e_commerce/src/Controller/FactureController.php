<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/facture', name: 'app_facture')]
class FactureController extends AbstractController
{
   
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR METTRE LA COMMANDE EN FACTURE

    #[Route('/ajout', name: 'add')]
    public function add(): Response
    {
        $commande = $session->get('commande', []);
        dd($commande); 


        return $this->render('facture/index.html.twig', [
            'controller_name' => 'FactureController',
        ]);
    }
}
