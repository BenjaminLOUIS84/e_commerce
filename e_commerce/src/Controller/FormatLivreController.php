<?php

namespace App\Controller;

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
        $formatLivre = $formatLivreRepository->findBy([],["livre_id" =>    // Pour récupérer la liste des formatLivre classées par date de publication ordre croissant
        "ASC"]);

        return $this->render('format/livre/index.html.twig', [
            'formatLivres' => $formatLivres
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR SUPPRIMER UN LIVRE

    #[Route('format/livre/{id}/delete', name: 'delete_format_livre')]   // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name

    public function delete(FormatLivre $formatLivre, EntityManagerInterface $entityManager): Response   

    {                                                                   // Créer une fonction delete() dans le controller pour supprimer un livre            
        $entityManager->remove($formatLivre);                                 // Supprime un livre
        $entityManager->flush();                                        // Exécute l'action DANS LA BDD

        $this->addFlash(                                                // Envoyer une notification
            'success',
            'Supprimé avec succès!'
        );

        return $this->redirectToRoute('app__format_livre');                     // Rediriger vers la liste des livres
       
    }

     //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION FORMULAIRE POUR AJOUTER et EDITER DES LIVRES

    #[Route('format/livre/new', name: 'new_format_livre')]                   // Reprendre la route en ajoutant /new à l'URL et en changeant le nom du name
    #[Route('format/livre/{id}/edit', name: 'edit_format_livre')]            // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name

    public function new_edit(FormatLivre $formatLivre  = null, Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager): Response   
    
    // Créer une fonction new() dans le controller pour permettre l'ajout de livre
    // Modifier celle-ci en new_edit pour permettre la modfication ou à défaut la création
    {
        if(!$formatLivre){                                           // S'il n'ya pas de livre à modifier alors en créer un nouveau
            $formatLivre = new FormatLivre();                              // Après avoir importé la classe Request Déclarer un nouveau livre
        }

        $form = $this->createForm(FormatLivreType :: class, $formatLivre); // Créer un nouveau formulaire avec la méthode createForm() et importer le classe LivreType
        //////////////////////////////////////////////////////////////////////////
        //                                                      GERER LE TRAITEMENT EN BDD
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {             // Si le formulaire soumis est valide alors
            
            $formatLivre = $form->getData();                              // Récupérer les informations du nouveau livre
            
            // $couvertureFile = $form->get('couverture')->getData();
            // $tomeFile = $form->get('tome')->getData();              // Récupérer les images (couverture et tome) du nouveau livre
            
            // $format = $form->get('formatLivres')->getData();        // Récupérer le format du livre

            //////////////////////////////////////////////////////////////////////////
            // Ces conditions sont nécessaires car les champs couverture et tome ne sont pas requis
            // Les fichiers jpeg doivent être priorisés seulement quand un fichier est chargé
            
            // if ($couvertureFile) {
                // Envoie les données au Service FileUploader 
                // $couvertureFileName = $fileUploader->upload($couvertureFile);
                // $livre->setCouverture($couvertureFileName);   
            // }

            // if ($tomeFile) {
                // Envoie les données au Service FileUploader 
                // $tomeFileName = $fileUploader->upload($tomeFile);
                // $livre->setTome($tomeFileName);
            // }

            // if ($format) {
            //     $livre->addFormatLivre($format);
            // }

            //////////////////////////////////////////////////////////////////////////

            //prepare PDO
            $entityManager->persist($formatLivre);                   // Dire à Doctrine que je veux sauvegarder le nouveau livre           
            //execute PDO
            $entityManager->flush();                           // Mettre le nouveau livre dans la BDD

            $this->addFlash(                                   // Envoyer une notification
                'success',
                'Opération réalisée avec succès!'
            );

            return $this->redirectToRoute('app__format_livre');        // Rediriger vers la liste des livres

        }

        //////////////////////////////////////////////////////////////////////////

        return $this->render('format/livre/new.html.twig', [          // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier livre)
            'formAddFormatLivre' => $form,
            'edit' => $formatLivre->getId()
        ]);
    }

}
