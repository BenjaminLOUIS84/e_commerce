<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConditonsGeneralesVenteController extends AbstractController
{
    #[Route('/conditons/generales', name: 'app_conditons_generales_vente')]
    public function index(): Response
    {
        return $this->render('conditons_generales_vente/index.html.twig', [
            'controller_name' => 'ConditonsGeneralesVenteController',           
        ]);
    }
}
