<?php

namespace App\Controller;                                               // La classe importée s'ajoute ici automatiquement

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    }                                                                   // Pour afficher cet argument dans la vue il faut créer un echo représenté par {{ }} Dans le fichier index.html.twig du dossier serie

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR SUPPRIMER UNE COLLECTION

    #[Route('/serie/{id}/delete', name: 'delete_serie')]                // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name

    public function delete(Serie $serie, EntityManagerInterface $entityManager): Response   

    {                                                                   // Créer une fonction delete() dans le controller pour supprimer une serie            
        $entityManager->remove($serie);                                 // Supprime une collection
        $entityManager->flush();                                        // Exécute l'action DANS LA BDD

        $this->addFlash(                                                // Envoyer une notification
            'success',
            'Supprimé avec succès!'
        );

        return $this->redirectToRoute('app_serie');                     // Rediriger vers la liste des collections
       
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION FORMULAIRE POUR AJOUTER et EDITER DES COLLECTIONS

    #[Route('/serie/new', name: 'new_serie')]                   // Reprendre la route en ajoutant /new à l'URL et en changeant le nom du name
    #[Route('/serie/{id}/edit', name: 'edit_serie')]            // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name

    public function new_edit(Serie $serie  = null, Request $request, EntityManagerInterface $entityManager): Response   
    
    // Créer une fonction new() dans le controller pour permettre l'ajout de collection
    // Modifier celle-ci en new_edit pour permettre la modfication ou à défaut la création

    {
        if(!$serie){                                            // S'il n'ya pas de collection à modifier alors en créer une nouvelle
            $serie = new Serie();                               // Après avoir importé la classe Request Déclarer une nouvelle collection
        }

        $form = $this->createForm(SerieType :: class, $serie);  // Créer un nouveau formulaire avec la méthode createForm() et importer le classe SerieType

        //////////////////////////////////////////////////////////////////////////
        //                                                      GERER LE TRAITEMENT EN BDD
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {         // Si le formulaire soumis est valide alors
            
            $serie = $form->getData();                          // Récupérer les informations de la nouvelle collection 
            //prepare PDO
            $entityManager->persist($serie);                    // Dire à Doctrine que je veux sauvegarder la nouvelle collection           
            //execute PDO
            $entityManager->flush();                            // Mettre la nouvelle collection dans la BDD

            $this->addFlash(                                    // Envoyer une notification
                'success',
                'Opération réalisée avec succès!'
            );

            return $this->redirectToRoute('app_serie');         // Rediriger vers la liste des collections
        }

        //////////////////////////////////////////////////////////////////////////


        return $this->render('serie/new.html.twig', [           // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier serie)
            'formAddSerie' => $form,
            'edit' => $serie->getId()
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LA LISTE DES LIVRES DE CHAQUE COLLECTION

    #[Route('/serie/{id}', name: 'show_serie')]                 // Reprendre la route en ajoutant /{id} à l'URL et en changeant le nom du name

    public function show(Serie $serie): Response                // Créer une fonction show() dans le controller pour afficher le détail d'une collection 

    {
        return $this->render('serie/show.html.twig', [          // Pour faire le lien entre le controller et la vue show.html.twig (il faut donc la créer dans le dossier collection)
            'serie' => $serie
        ]);
    }
}                                                                       

