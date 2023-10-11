<?php

namespace App\Controller;

use App\Entity\Commentaire;

use App\Form\CommentaireType;
use App\Entity\Newsletters\Newsletters;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\Newsletters\NewslettersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commentaire', name: 'app_commentaire_')]
class CommentaireController extends AbstractController
{

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION pour préparer et ajouter les commentaires

    #[Route('/prepare', name: 'prepare')]

    #[Route('/prepare/{id}/edit', name: 'edit')]
    // #[Route('/prepare/{slug}/edit', name: 'edit')]

    public function prepare(
        
        Commentaire $commentaire = null,
        Request $request,
        EntityManagerInterface $entityManager,
        // NewslettersRepository $newslettersRepository
        Newsletters $newsletters

    ): Response

    {
        // $id = 7;
        
        // $newsletters = $newslettersRepository->findOneBy(['id' => $id]);           // Rechercher la newsletter par son id

        // $newsletters = $newslettersRepository->find($id);           // Rechercher la newsletter par son id
        
        // dd($newsletters);
        

        if(!$commentaire){
            $commentaire = new commentaire();                               // Créer un commentaire s'il n'y en a pas
            $commentaire->setUser($this->getUser());                        // Injecter l'utilisateur (auteur du commentaire)
            
            $commentaire->setNewsletters($newsletters);                     // Injecter la newsletter concernée                              
        }                   

        $form = $this->createForm(CommentaireType :: class, $commentaire);  // Créer le formulaire
        $form->handleRequest($request);                                     // Activer le formulaire

        if ($form->isSubmitted() && $form->isValid()) {                     // Si le formulaire soumis est valide alors

            $commentaire = $form->getData();                                // Récupérer les informations du fomulaire
            
            // Prepare PDO
            $entityManager->persist($commentaire);                          // Dire à Doctrine que je veux sauvegarder la nouveau commentaire          
            // Execute PDO
            $entityManager->flush();                                        // Mettre le nouveau commentaire dans la BDD
        
            $this->addFlash('message', 'Commentaire ajouté avec succès');
            return $this->redirectToRoute('app_newsletters_list');
        }

        return $this->render('commentaire/prepare.html.twig', [
            'form' => $form->createView(),
            'edit' => $commentaire->getId(),
        ]);
        
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION pour supprimer les commentaires

    #[Route('/commentaire/{id}/delete', name: 'delete_commentaire')]                    // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name
    
    // #[Route('/commentaire/{slug}/delete', name: 'delete_commentaire')]                    // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name

    public function delete(Commentaire $commentaire, EntityManagerInterface $entityManager): Response   
    {                                                                                   // Créer une fonction delete() dans le controller pour supprimer un commentaire            
        $entityManager->remove($commentaire);                                           // Supprime une commentaire
        $entityManager->flush();                                                        // Exécute l'action DANS LA BDD

        $this->addFlash(                                                                // Envoyer une notification
            'success',
            'Supprimé avec succès!'
        );

        return $this->redirectToRoute('app_newsletters_list');                               // Rediriger vers la liste des newsletterss
    }

}
