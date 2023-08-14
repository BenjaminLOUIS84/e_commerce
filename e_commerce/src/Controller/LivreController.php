<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION FORMULAIRE POUR AJOUTER et EDITER DES LIVRES

    #[Route('/livre/new', name: 'new_livre')]                   // Reprendre la route en ajoutant /new à l'URL et en changeant le nom du name
    #[Route('/livre/{id}/edit', name: 'edit_livre')]            // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name

    public function new_edit(Livre $livre  = null, Request $request, EntityManagerInterface $entityManager): Response   
    
    // Créer une fonction new() dans le controller pour permettre l'ajout de livre
    // Modifier celle-ci en new_edit pour permettre la modfication ou à défaut la création

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // POUR PERMETTRE LA MODIFICATION DES LIVRE -> rendre nullable les propriétés couverture et tome (fichier type) dans la bdd
    //                                          -> dans le fichier LivreType.php, ajouter 'required'=> false aux champs couverture et tome
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    {
        if(!$livre){                                           // S'il n'ya pas de livre à modifier alors en créer un nouveau
            $livre = new Livre();                              // Après avoir importé la classe Request Déclarer un nouveau livre
        }

        $form = $this->createForm(LivreType :: class, $livre); // Créer un nouveau formulaire avec la méthode createForm() et importer le classe LivreType

        //////////////////////////////////////////////////////////////////////////
        //                                                      GERER LE TRAITEMENT EN BDD
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {        // Si le formulaire soumis est valide alors
            
            $livre = $form->getData();                         // Récupérer les informations du nouveau livre
            //prepare PDO
            $entityManager->persist($livre);                   // Dire à Doctrine que je veux sauvegarder le nouveau livre           
            //execute PDO
            $entityManager->flush();                           // Mettre le nouveau livre dans la BDD

            return $this->redirectToRoute('app_livre');        // Rediriger vers la liste des livres
        }

        //////////////////////////////////////////////////////////////////////////


        return $this->render('livre/new.html.twig', [          // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier livre)
            'formAddLivre' => $form,
            'edit' => $livre->getId()
        ]);
    }
}                                                                       


