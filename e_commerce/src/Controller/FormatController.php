<?php

namespace App\Controller;

use App\Entity\Format;
use App\Form\FormatType;
use App\Service\FileUploader;
use App\Repository\LivreRepository;
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

    public function new_edit(Format $format  = null, Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager): Response   
    
    // Créer une fonction new() dans le controller pour permettre l'ajout de format
    // Modifier celle-ci en new_edit pour permettre la modfication ou à défaut la création

    {
        if(!$format){                                            // S'il n'ya pas de format à modifier alors en créer un nouveau
            $format = new Format();                              // Après avoir importé la classe Request Déclarer un nouveau format
        }

        $form = $this->createForm(FormatType :: class, $format); // Créer un nouveau formulaire avec la méthode createForm() et importer le classe FormatType

        //////////////////////////////////////////////////////////////////////////
        //                                                      GERER LE TRAITEMENT EN BDD
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {         // Si le formulaire soumis est valide alors
            
            $format = $form->getData();                         // Récupérer les informations du nouveau format
            
            $photoFile = $form->get('photo')->getData();        // Récupérer l'image (photo) du nouveau format  
            
            //////////////////////////////////////////////////////////////////////////
            // Cette condition est nécessaire car le champs photo n'est pas requis
            // Les fichiers jpeg doivent être priorisés seulement quand un fichier est chargé
            
            if ($photoFile) {
                // Envoie les données au Service FileUploader 
                $photoFileName = $fileUploader->upload($photoFile);
                $format->setPhoto($photoFileName);   
            }

            //////////////////////////////////////////////////////////////////////////

            //prepare PDO
            $entityManager->persist($format);                   // Dire à Doctrine que je veux sauvegarder le nouveau format           
            //execute PDO
            $entityManager->flush();                            // Mettre le nouveau format dans la BDD

            $this->addFlash(                                    // Envoyer une notification
                'success',
                'Opération réalisée avec succès!'
            );

            return $this->redirectToRoute('app_format');        // Rediriger vers la liste des formats
        }

        //////////////////////////////////////////////////////////////////////////


        return $this->render('format/new.html.twig', [           // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier format)
            'formAddFormat' => $form,
            'edit' => $format->getId()
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE DETAIL DE CHAQUE MODES D'EDITION

    #[Route('/format/{id}', name: 'show_format')]                 // Reprendre la route en ajoutant /{id} à l'URL et en changeant le nom du name

    public function show(Format $format): Response                // Créer une fonction show() dans le controller pour afficher le détail d'un mode d'édition 

    {
        return $this->render('format/show.html.twig', [          // Pour faire le lien entre le controller et la vue show.html.twig (il faut donc la créer dans le dossier collection)
            'format' => $format
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LA LISTE DES LIVRES PAR FORMAT

    #[Route('/format/{id}/book', name: 'list_livre')]                        // Route représentant l'URL '/livre' pour la redirection et le name: sert pour la navigation
    
    public function book(Format $format, LivreRepository $livreRepository): Response   // Pour afficher la liste des livres insérer dans la fonction index() livreRepository $livreRepository et Importer la classe LivreRepository avec un click droit
    {                                                                                                                       
        $livres = $livreRepository->findBy([],["date_publication" =>    // Pour récupérer la liste des livres classées par date de publication ordre croissant
        "ASC"]);      

        return $this->render('format/book.html.twig', [                 // render() Permet de faire le lien entre le controller et la view
            'livres' => $livres,                                        // Pour passer la variable $livres en argument 'livres'                                     
            'format' => $format,                                                                                                                                                 
        ]);
    }        
}                                                                          
