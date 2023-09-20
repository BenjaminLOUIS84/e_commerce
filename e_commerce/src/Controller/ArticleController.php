<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController  // Afficher la liste de tous les articles classés par date ASC
{
    #[Route('/article', name: 'app_article')]

    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy([], ["id" => "ASC"]);

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles
        ]);
    }
}
