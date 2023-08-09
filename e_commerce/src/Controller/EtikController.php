<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtikController extends AbstractController
{
    #[Route('/etik', name: 'app_etik')]
    public function index(): Response
    {
        return $this->render('etik/index.html.twig', [
            'controller_name' => 'EtikController',
        ]);
    }
}
