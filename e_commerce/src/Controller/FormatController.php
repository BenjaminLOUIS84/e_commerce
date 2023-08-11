<?php

namespace App\Controller;

use App\Entity\Format;
use App\Repository\FormatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormatController extends AbstractController
{                                                                           // AFFICHER LA LISTE DES FORMATS
    #[Route('/format', name: 'app_format')]                                 // Route représentant l'URL '/format' pour la redirection et le name: sert pour la navigation
    public function index(FormatRepository $formatRepository): Response     // Pour afficher la liste des collections insérer dans la fonction index() formatRepository $formatRepository        
    {                                                                       // Importer la classe FormatRepository avec un click droit 
        $formats = $formatRepository->findBy([],["type" => "ASC"]);         // Pour récupérer la liste des formats classées par ordre alphabéthique selon l'intitule

        return $this->render('format/index.html.twig', [                    // render() Permet de faire le lien entre le controller et la view
            'formats' => $formats                                           // Pour passer la variable $formats en argument 'formats'
        ]);
    }                                                                       // Pour afficher cet argument dans la vue il faut créer un echo représenté par {{ }} Dans le fichier index.html.twig du dossier format

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR SUPPRIMER UN FORMAT

    #[Route('/format/{id}/delete', name: 'delete_format')]                  // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name

    public function delete(Format $format, EntityManagerInterface $entityManager): Response   

    {                                                                       // Créer une fonction delete() dans le controller pour supprimer un format            
        $entityManager->remove($format);                                    // Supprime un format
        $entityManager->flush();                                            // Exécute l'action DANS LA BDD

        return $this->redirectToRoute('app_format');                        // Rediriger vers la liste des formats
       
    }

}                                                                          
