<?php

namespace App\Controller;

use App\Form\CategoriesType;
use App\Entity\Newsletters\Categories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\Newsletters\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoriesController extends AbstractController
{
    // #[IsGranted("ROLE_ADMIN")]
    #[Route('/categories', name: 'app_categories')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findAll();

        return $this->render('categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/categories/{slug}-{id<[0-9]+>}', name: 'show_categories', requirements: ['slug' => '[a-z0-9\-]*'])]
    public function show(Categories $categories, string $slug): Response
    {
        if($categories->getSlug() !== $slug){
            return $this->redirectToRoute('show_categories', [
                'id' =>$categories->getId(),
                'slug' => $categories->getSlug(),
            ], 301);
        }

        return $this->render('categories/show.html.twig', [
            'categories' => $categories,

        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR SUPPRIMER UNE CATEGORIE
    
    #[Route('/categories/{slug}-{id<[0-9]+>}/delete', name: 'delete_categories', requirements: ['slug' => '[a-z0-9\-]*'])]
    public function delete(Categories $categories, EntityManagerInterface $entityManager, string $slug): Response   

    {                                                                   // Créer une fonction delete() dans le controller pour supprimer une categories            
        
        //$this->denyAccesUnlessGranted('ROLE_ADMIN');                 // Permet de vérifier si un admin est connecté pour effectuer cette action
        if (!$this->isGranted('ROLE_ADMIN')) {                          // Permet d'empécher l'accès à cette action si ce n'est pas un admin
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        if($categories->getSlug() !== $slug){
            return $this->redirectToRoute('app_categories', [
                'id' =>$categories->getId(),
                'slug' => $categories->getSlug(),
            ], 301);
        }
        
        $entityManager->remove($categories);                            // Supprime une CATEGORIE
        $entityManager->flush();                                        // Exécute l'action DANS LA BDD

        $this->addFlash(                                                // Envoyer une notification
            'success',
            'Supprimé avec succès!'
        );

        return $this->redirectToRoute('app_categories');                // Rediriger vers la liste des CATEGORIES
       
    }

     //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION FORMULAIRE POUR AJOUTER et EDITER DES CATEGORIES
    
    #[Route('/categories/new', name: 'new_categories')]                   // Reprendre la route en ajoutant /new à l'URL et en changeant le nom du name
    #[Route('/categories/{id}/edit', name: 'edit_categories')]            // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name
    
    public function new_edit(Categories $categories  = null, Request $request, EntityManagerInterface $entityManager): Response   
    
    {

        if (!$this->isGranted('ROLE_ADMIN')) {                          // Permet d'empécher l'accès à cette action si ce n'est pas un admin
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        if(!$categories){                                                 // S'il n'ya pas de catégorie à modifier alors en créer une nouvelle
            $categories = new Categories();                               // Après avoir importé la classe Request Déclarer une nouvelle catégorie
        }

        $form = $this->createForm(CategoriesType :: class, $categories);  // Créer un nouveau formulaire avec la méthode createForm() et importer le classe categoriesType

        //////////////////////////////////////////////////////////////////////////
        //                                                      GERER LE TRAITEMENT EN BDD
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {         // Si le formulaire soumis est valide alors
            
            $categories = $form->getData();                     // Récupérer les informations de la nouvelle catégorie 
            //prepare PDO
            $entityManager->persist($categories);               // Dire à Doctrine que je veux sauvegarder la nouvelle catégorie           
            //execute PDO
            $entityManager->flush();                            // Mettre la nouvelle catégorie dans la BDD

            $this->addFlash(                                    // Envoyer une notification
                'success',
                'Opération réalisée avec succès!'
            );

            return $this->redirectToRoute('app_categories');    // Rediriger vers la liste des catégories
        }

        //////////////////////////////////////////////////////////////////////////

        return $this->render('categories/new.html.twig', [      // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier categories)
            'formAddCategories' => $form,
            'edit' => $categories->getId()
        ]);
    }


}
