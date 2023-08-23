<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Entity\FormatLivre;
use App\Service\FileUploader;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FormatLivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class LivreController extends AbstractController
{                                                                       // AFFICHER LA LISTE DES LIVRES
    #[Route('/livre', name: 'app_livre')]                               // Route représentant l'URL '/livre' pour la redirection et le name: sert pour la navigation
    
    public function index(LivreRepository $livreRepository,             // Pour afficher la liste des livres insérer dans la fonction index() livreRepository $livreRepository et Importer la classe LivreRepository avec un click droit
    FormatLivreRepository $formatLivreRepository,                       // Pour permettre l'affichage des prix unitaires et des formats insérer dans la fonction index() formatLivreRepository $formatLivreRepository et Importer la classe FormatLivreRepository avec un click droit
    // FormatLivre $formatLivre                                            
    
    ): Response          
    
    {                                                                                                                       
        $livres = $livreRepository->findBy([],["date_publication" =>    // Pour récupérer la liste des livres classées par date de publication ordre croissant
        "ASC"]);      

        $formatLivres = $formatLivreRepository->findAll();              // Pour récupérer la liste de tous les prix unitaire et de tous les formats

        // $formatLivre = $formatLivreRepository->find($id);            // Pour récupérer un prix unitaire et un format selon l'ID
        // $formatLivre ->getId();

        return $this->render('livre/index.html.twig', [                 // render() Permet de faire le lien entre le controller et la view
            'livres' => $livres,                                        // Pour passer la variable $livres en argument 'livres'
            'formatLivres' => $formatLivres,                            // Pour passer la variable $formatLivres en argument 'formatLivres'

            // 'formatLivre' => $formatLivre                            // Pour passer la variable $formatLivre en argument 'formatLivre'
        ]);
    }                                                                   // Pour afficher cet argument dans la vue il faut créer un echo représenté par {{ }} Dans le fichier index.html.twig du dossier livre

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR SUPPRIMER UN LIVRE

    #[Route('/livre/{id}/delete', name: 'delete_livre')]                // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name

    public function delete(Livre $livre, EntityManagerInterface $entityManager): Response   

    {                                                                   // Créer une fonction delete() dans le controller pour supprimer un livre            
        $entityManager->remove($livre);                                 // Supprime un livre
        $entityManager->flush();                                        // Exécute l'action DANS LA BDD

        $this->addFlash(                                                // Envoyer une notification
            'success',
            'Supprimé avec succès!'
        );

        return $this->redirectToRoute('app_livre');                     // Rediriger vers la liste des livres
       
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION FORMULAIRE POUR AJOUTER et EDITER DES LIVRES

    #[Route('/livre/new', name: 'new_livre')]                   // Reprendre la route en ajoutant /new à l'URL et en changeant le nom du name
    #[Route('/livre/{id}/edit', name: 'edit_livre')]            // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name

    public function new_edit(Livre $livre  = null, Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager): Response   
    
    // Créer une fonction new() dans le controller pour permettre l'ajout de livre
    // Modifier celle-ci en new_edit pour permettre la modfication ou à défaut la création
    {
        if(!$livre){                                           // S'il n'ya pas de livre à modifier alors en créer un nouveau
            $livre = new Livre();                              // Après avoir importé la classe Request Déclarer un nouveau livre
        }

        $form = $this->createForm(LivreType :: class, $livre); // Créer un nouveau formulaire avec la méthode createForm() et importer le classe LivreType
        //////////////////////////////////////////////////////////////////////////
        //                                                      GERER LE TRAITEMENT EN BDD
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {             // Si le formulaire soumis est valide alors
            
            $livre = $form->getData();                              // Récupérer les informations du nouveau livre
            
            $couvertureFile = $form->get('couverture')->getData();
            $tomeFile = $form->get('tome')->getData();              // Récupérer les images (couverture et tome) du nouveau livre
            
            //////////////////////////////////////////////////////////////////////////
            // Ces conditions sont nécessaires car les champs couverture et tome ne sont pas requis
            // Les fichiers jpeg doivent être priorisés seulement quand un fichier est chargé
            
            if ($couvertureFile) {
                // Envoie les données au Service FileUploader 
                $couvertureFileName = $fileUploader->upload($couvertureFile);
                $livre->setCouverture($couvertureFileName);   
            }

            if ($tomeFile) {
                // Envoie les données au Service FileUploader 
                $tomeFileName = $fileUploader->upload($tomeFile);
                $livre->setTome($tomeFileName);
            }

            //////////////////////////////////////////////////////////////////////////

            //prepare PDO
            $entityManager->persist($livre);                   // Dire à Doctrine que je veux sauvegarder le nouveau livre           
            //execute PDO
            $entityManager->flush();                           // Mettre le nouveau livre dans la BDD

            $this->addFlash(                                   // Envoyer une notification
                'success',
                'Opération réalisée avec succès!'
            );

            return $this->redirectToRoute('app_livre');        // Rediriger vers la liste des livres

        }

        //////////////////////////////////////////////////////////////////////////

        return $this->render('livre/new.html.twig', [          // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier livre)
            'form' => $form,
            'edit' => $livre->getId(),
            'livreId' => $livre->getId()
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE DETAIL DE CHAQUE LIVRES

    // #[Route('/livre/{id}', name: 'show_livre')]                 // Reprendre la route en ajoutant /{id} à l'URL et en changeant le nom du name

    // public function show(Livre $livre, FormatLivre $formatLivre): Response   // Créer une fonction show() dans le controller pour afficher le détail d'un livre 

    // {
    //     return $this->render('livre/show.html.twig', [          // Pour faire le lien entre le controller et la vue show.html.twig (il faut donc la créer dans le dossier livre)
    //         'formatLivre' => $formatLivre
    //     ]);
    // }

}                                                                       
