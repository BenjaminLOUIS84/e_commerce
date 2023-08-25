<?php

namespace App\Controller;

use App\Entity\Livre;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/panier', name: 'panier_')]
class PanierController extends AbstractController
{
    #[Route('/add/{id}', name: 'add')]
    public function add(Livre $livre, SessionInterface $session)
    {

        dd($session); // Pour dumper la variable session et voir ce qu'il y a dedans

    }
}
