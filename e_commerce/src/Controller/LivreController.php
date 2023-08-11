<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class LivreController extends AbstractController
{                                                                       // AFFICHER LA LISTE DES LIVRES
    #[Route('/livre', name: 'app_livre')]                               // Route représentant l'URL '/livre' pour la redirection et le name: sert pour la navigation
    public function index(LivreRepository $livreRepository): Response   // Pour afficher la liste des livres insérer dans la fonction index() livreRepository $livreRepository        
    {                                                                   // Importer la classe LivreRepository avec un click droit 
        $livres = $livreRepository->findBy([],["titre" => "ASC"]);      // Pour récupérer la liste des livres classées par ordre alphabéthique selon le titre

        return $this->render('livre/index.html.twig', [                 // render() Permet de faire le lien entre le controller et la view
            'livres' => $livres                                         // Pour passer la variable $livres en argument 'livres'
        ]);
    }                                                                   // Pour afficher cet argument dans la vue il faut créer un echo représenté par {{ }} Dans le fichier index.html.twig du dossier livre

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR SUPPRIMER UN LIVRE

    #[Route('/livre/{id}/delete', name: 'delete_livre')]                // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name

    public function delete(Livre $livre, EntityManagerInterface $entityManager): Response   

    {                                                                   // Créer une fonction delete() dans le controller pour supprimer un livre            
        $entityManager->remove($livre);                                 // Supprime un livre
        $entityManager->flush();                                        // Exécute l'action DANS LA BDD

        return $this->redirectToRoute('app_livre');                     // Rediriger vers la liste des livres
       
    }
}                                                                       


