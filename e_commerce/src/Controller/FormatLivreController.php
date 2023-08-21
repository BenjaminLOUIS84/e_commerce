<?php

namespace App\Controller;

use App\Entity\Format;
use App\Entity\Livre;
use App\Entity\FormatLivre;
use App\Form\FormatLivreType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FormatLivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FormatLivreController extends AbstractController
{
    #[Route('/format/livre', name: 'app_format_livre')]
    
    public function index(FormatLivreRepository $formatLivreRepository): Response
    {
        $formatLivres = $formatLivreRepository->findAll();

        return $this->render('format_livre/index.html.twig', [

            'formatLivres' => $formatLivres
        ]);
    }
    
}
