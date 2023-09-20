<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController  // Afficher la liste de tous les articles classés par date ASC
{
    #[Route('/article', name: 'app_article')]

    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy([], ["id" => "ASC"]);

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR SUPPRIMER UN ARTICLE

    #[Route('/article/{id}/delete', name: 'delete_article')]                // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name

    public function delete(Article $article, EntityManagerInterface $entityManager): Response   
    {                                                                   // Créer une fonction delete() dans le controller pour supprimer un article            
        $entityManager->remove($article);                                 // Supprime un article
        $entityManager->flush();                                        // Exécute l'action DANS LA BDD

        $this->addFlash(                                                // Envoyer une notification
            'success',
            'Supprimé avec succès!'
        );

        return $this->redirectToRoute('app_article');                     // Rediriger vers la liste des articles
       
    }

     //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION FORMULAIRE POUR AJOUTER et EDITER DES ARTICLES

    #[Route('/article/new', name: 'new_article')]                   // Reprendre la route en ajoutant /new à l'URL et en changeant le nom du name
    #[Route('/article/{id}/edit', name: 'edit_article')]            // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name

    public function new_edit(
        Article $article  = null,
        Request $request, 
        // FileUploader $fileUploader, 
        EntityManagerInterface $entityManager
        
        ): Response   
    
    // Créer une fonction new() dans le controller pour permettre l'ajout de article
    // Modifier celle-ci en new_edit pour permettre la modfication ou à défaut la création
    {
        if(!$article){                                           // S'il n'ya pas de article à modifier alors en créer un nouveau
            $article = new Article();                              // Après avoir importé la classe Request Déclarer un nouveau article
        }

        $form = $this->createForm(ArticleType :: class, $article); // Créer un nouveau formulaire avec la méthode createForm() et importer le classe articleType
        //////////////////////////////////////////////////////////////////////////
        //                                                      GERER LE TRAITEMENT EN BDD
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {             // Si le formulaire soumis est valide alors
            
            $article = $form->getData();                              // Récupérer les informations du nouveau article
            
            // $couvertureFile = $form->get('couverture')->getData();
            // $tomeFile = $form->get('tome')->getData();              // Récupérer les images (couverture et tome) du nouveau article
            
            //////////////////////////////////////////////////////////////////////////
            // Ces conditions sont nécessaires car les champs couverture et tome ne sont pas requis
            // Les fichiers jpeg doivent être priorisés seulement quand un fichier est chargé
            
            // if ($couvertureFile) {
                // Envoie les données au Service FileUploader 
                // $couvertureFileName = $fileUploader->upload($couvertureFile);
                // $article->setCouverture($couvertureFileName);   
            // }

            // if ($tomeFile) {
                // Envoie les données au Service FileUploader 
                // $tomeFileName = $fileUploader->upload($tomeFile);
                // $article->setTome($tomeFileName);
            // }

            //////////////////////////////////////////////////////////////////////////

            //prepare PDO
            $entityManager->persist($article);                   // Dire à Doctrine que je veux sauvegarder le nouveau article           
            //execute PDO
            $entityManager->flush();                           // Mettre le nouveau article dans la BDD

            $this->addFlash(                                   // Envoyer une notification
                'success',
                'Opération réalisée avec succès!'
            );

            return $this->redirectToRoute('app_article');        // Rediriger vers la liste des articles

        }

        //////////////////////////////////////////////////////////////////////////

        return $this->render('article/new.html.twig', [          // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier article)
            'form' => $form,
            'edit' => $article->getId()
        ]);
    }

}
