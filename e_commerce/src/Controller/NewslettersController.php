<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewslettersController extends AbstractController
{
    #[Route('/newsletters', name: 'app_newsletters')]
    public function index(): Response
    {
        return $this->render('newsletters/index.html.twig', [
            'controller_name' => 'NewslettersController',
        ]);
    }
}
