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

    public function prepare(
        
        Commentaire $commentaire = null,
        Request $request,
        EntityManagerInterface $entityManager,
        NewslettersRepository $newslettersRepository
        
    ): Response

    {                          
        $id = $request->query->get('id');                                   // Instancier l'id selon le choix de la newsletter
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // $id = 7;                                                         // Instancier l'id séléctionner par défaut 

        $newsletters = $newslettersRepository->findOneBy(['id' => $id]);    // Rechercher la newsletter par son id
       
        // dd($newsletters);                                                   // Vérifier ce qui est récupéré

        // Condition pour empécher de modifier l'Id dans l'Url pour éviter de modifier ou supprimer les commentaires des autres

        // if (!$this->isGranted('ROLE_ADMIN')) {                              // Permet d'empécher l'accès à cette action si ce n'est pas un admin
        //     throw $this->createAccessDeniedException('Accès non autorisé');
        // }
        
        // if ($this->getUser() != $user) {                                // Permet d'empécher l'accès à cette action si l'id dans l'URL ne correspond pas à celui de l'utilisateur
        // if ($this->getUser() != $user) {                                // Permet d'empécher l'accès à cette action si l'id dans l'URL ne correspond pas à celui de l'utilisateur
            
        //     throw $this->createAccessDeniedException('Accès non autorisé');
        // }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        if(!$commentaire){
            $commentaire = new commentaire();                               // Créer un commentaire s'il n'y en a pas
            $commentaire->setUser($this->getUser());                        // Injecter l'utilisateur (auteur du commentaire)
            
            $commentaire->setNewsletters($newsletters);                     // Injecter la newsletter concernée                                             
        }                   
       

        $form = $this->createForm(CommentaireType :: class, $commentaire);  // Créer le formulaire
        $form->handleRequest($request);                                     // Activer le formulaire

        if ($form->isSubmitted() && $form->isValid()) {                     // Si le formulaire soumis est valide alors

            // On vérifie si le champ "recaptcha-response" contient une valeur/////////CAPTCHA
            if(empty($_POST['recaptcha-response'])){
                header('Location: app_commentaire_prepare, id: newsletters.id'); 

            }else{  // On prépare l'URL
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=6LemV_MnAAAAAMVu3oth8lvd3LVLOXoH7FMdKuJt&response={$_POST['recaptcha-response']}";

                // On vérifie si CURL est installé
                if(function_exists('curl_version')){
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_HEADER, false);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_TIMEOUT, 1);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($curl);
                }else{
                    $response = file_get_contents($url);
                }

                // On vérifie si on a une réponse
                if(empty($response) || is_null($response)){
                    header('Location: app_commentaire_prepare, id: newsletters.id'); 
                }else{
                    $data = json_decode($response);
                    if($data->success){

                        $commentaire = $form->getData();                                // Récupérer les informations du fomulaire
            
                        // Prepare PDO
                        $entityManager->persist($commentaire);                          // Dire à Doctrine que je veux sauvegarder la nouveau commentaire          
                        // Execute PDO
                        $entityManager->flush();                                        // Mettre le nouveau commentaire dans la BDD
                    
                        $this->addFlash('message', 'Commentaire ajouté avec succès');
                        return $this->redirectToRoute('app_newsletters_list');

                    }else{
                        header('Location: app_commentaire_prepare, id: newsletters.id'); 
                    }
                }   
            }  
        }

        return $this->render('commentaire/prepare.html.twig', [
            'form' => $form->createView(),
            'edit' => $commentaire->getId(),
            
        ]);
        
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION pour supprimer les commentaires

    #[Route('/commentaire/{id}/delete', name: 'delete_commentaire')]                    // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name
    
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
