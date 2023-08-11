<?php

namespace App\Controller;                                               // La classe importée s'ajoute ici automatiquement

use App\Repository\SerieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SerieController extends AbstractController
{                                                                       // AFFICHER LA LISTE DES COLLECTIONS
    #[Route('/serie', name: 'app_serie')]                               // Route représentant l'URL '/serie' pour la redirection et le name: sert pour la navigation
    public function index(SerieRepository $serieRepository): Response   // Pour afficher la liste des collections insérer dans la fonction index() SerieRepository $serieRepository        
    {                                                                   // Importer la classe SerieRepository avec un click droit 
        $series = $serieRepository->findBy([],["intitule" => "ASC"]);   // Pour récupérer la liste des series classées par ordre alphabéthique selon l'intitule

        return $this->render('serie/index.html.twig', [                 // render() Permet de faire le lien entre le controller et la view
            'series' => $series                                         // Pour passer la variable $series en argument 'series'
        ]);
    }                                                                   // Pour afficher cet argument dans la vue il faut créer un echo représenté par {{ }}
}                                                                       // Dans le fichier index.html.twig du dossier serie
