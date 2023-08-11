<?php

namespace App\Controller;

use App\Entity\Format;
use App\Form\FormatType;
use App\Repository\FormatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION FORMULAIRE POUR AJOUTER et EDITER DES FORMATS

    #[Route('/format/new', name: 'new_format')]                   // Reprendre la route en ajoutant /new à l'URL et en changeant le nom du name
    #[Route('/format/{id}/edit', name: 'edit_format')]            // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name

    public function new_edit(Format $format  = null, Request $request, EntityManagerInterface $entityManager): Response   
    
    // Créer une fonction new() dans le controller pour permettre l'ajout de format
    // Modifier celle-ci en new_edit pour permettre la modfication ou à défaut la création

    {
        if(!$format){                                            // S'il n'ya pas de format à modifier alors en créer un nouveau
            $format = new Format();                              // Après avoir importé la classe Request Déclarer un nouveau format

        $form = $this->createForm(FormatType :: class, $format); // Créer un nouveau formulaire avec la méthode createForm() et importer le classe FormatType

        //////////////////////////////////////////////////////////////////////////
        //                                                      GERER LE TRAITEMENT EN BDD
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {         // Si le formulaire soumis est valide alors
            
            $format = $form->getData();                         // Récupérer les informations du nouveau format
            //prepare PDO
            $entityManager->persist($format);                   // Dire à Doctrine que je veux sauvegarder le nouveau format           
            //execute PDO
            $entityManager->flush();                            // Mettre le nouveau format dans la BDD

            return $this->redirectToRoute('app_format');        // Rediriger vers la liste des formats
        }

        //////////////////////////////////////////////////////////////////////////


        return $this->render('format/new.html.twig', [           // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier format)
            'formAddFormat' => $form,
            'edit' => $format->getId()
        ]);
    }
}                                                                          
