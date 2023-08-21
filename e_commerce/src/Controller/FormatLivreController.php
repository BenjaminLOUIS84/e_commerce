<?php

namespace App\Controller;

use App\Repository\FormatLivreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormatLivreController extends AbstractController
{
    #[Route('/formatLivre', name: 'app_format_livre')]
    
    public function index(FormatLivreRepository $formatLivreRepository): Response
    {
        $formatLivres = $formatLivreRepository->findAll();

        return $this->render('format_livre/index.html.twig', [

            'formatLivres' => $formatLivres
        ]);
    }
}
