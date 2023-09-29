<?php

namespace App\Controller;

use App\Form\NewslettersType;
use App\Service\FileUploader;
use App\Entity\Newsletters\Users;
use App\Form\NewslettersUsersType;
use App\Entity\Newsletters\Newsletters;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\Newsletters\NewslettersRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/newsletters', name: 'app_newsletters_')]
class NewslettersController extends AbstractController
{
    // FONCTION pour inscire un utilisateur aux newsletters

    #[Route('/', name: 'home')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new Users();
        $form = $this->createForm(NewslettersUsersType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {             // Si le formulaire soumis est valide alors
       
            $token = hash('sha256', uniqid());
            $user->setValidationToken($token);
        
            $entityManager->persist($user);
            $entityManager->flush();

            //$url = $this->generateUrl('app_newsletters_confirm, id: user.id, token: token',[] , UrlGeneratorInterface::ABSOLUTE_URL); 

            $email = (new TemplatedEmail())
            ->from('etrefouetsage@gmail.com')
            ->to($user->getEmail())
            ->subject('Votre inscritpion à la newsletter')
            ->htmlTemplate('email/inscription.html.twig')
            ->context(compact( 
                //'url', 
                'user', 
                'token'
                ))
            ;

            $mailer->send($email);

            $this->addFlash('message', 'Inscription en attente de validation');
            return $this->redirectToRoute('app_home');
        }

        
        return $this->render('newsletters/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // FONCTION pour valider l'inscription par mail

    #[Route('/confirm/{id}/{token}', name: 'confirm')]
    public function confirm(Users $user, $token, EntityManagerInterface $entityManager): Response
    {
        if($user->getValidationToken() != $token){
            throw $this->createNotFoundException('Page non trouvée');
        }

        $user->setIsValid(true);
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('message', 'Inscription validée');
        return $this->redirectToRoute('app_home');
    }

    // FONCTION pour préparer les newsletters

    #[Route('/prepare', name: 'prepare')]
    #[Route('/prepare/{id}/edit', name: 'edit')]
    public function prepare(
        Newsletters $newsletters = null,
        Request $request,
        FileUploader $fileUploader,
        EntityManagerInterface $entityManager, 

    ): Response

    {
        if(!$newsletters){
            $newsletters = new Newsletters();                              // Créer une newsletter s'il n'y en a pas
        }
                                        

        $form = $this->createForm(NewslettersType :: class, $newsletters);  // Créer le formulaire
        $form->handleRequest($request);                                     // Activer le formulaire

        if ($form->isSubmitted() && $form->isValid()) {                     // Si le formulaire soumis est valide alors
            
            $newsletters = $form->getData();                                // Récupérer les informations 
            
            $pictureFile = $form->get('picture')->getData();                // Récupérer les images
            //////////////////////////////////////////////////////////////////////////
            // Les fichiers jpeg doivent être priorisés seulement quand un fichier est chargé
            
            if ($pictureFile) {                                             //Envoie les données au Service FileUploader 
                $pictureFileName = $fileUploader->upload($pictureFile);
                $newsletters->setpicture($pictureFileName);   
            }
            //////////////////////////////////////////////////////////////////////////

            // Prepare PDO
            $entityManager->persist($newsletters);                        // Dire à Doctrine que je veux sauvegarder la nouvelle newsletter          
            // Execute PDO
            $entityManager->flush();                                        // Mettre la nouvelle newsletterdans la BDD
        
            return $this->redirectToRoute('app_newsletters_list');
        }

        return $this->render('newsletters/prepare.html.twig', [
            'form' => $form->createView(),
            'edit' => $newsletters->getId()
        ]);
        
    }
    
    // FONCTION pour afficher les newsletters dans un fil d'actualité

    #[Route('/list', name: 'list')]
    public function list(NewslettersRepository $newslettersRepository): Response

    {

        $newsletters = $newslettersRepository->findBy([], ["created_at" => "DESC"]);    // Classer les newsletters par date de publication du plus récent au plus ancien DESC

        return $this->render('newsletters/list.html.twig', [                            // Emplacement et disposition de la vue 
            'controller_name' => 'NewslettersController',
            'newsletters' => $newsletters
        ]);
    }

    // FONCTION pour envoyer par mail les newsletters à tous les utilisateurs inscrit à la newsletters

    #[Route('/send/{id}', name: 'send')]
    public function send(Newsletters $newsletters, MailerInterface $mailer): Response

    {
       $users = $newsletters->getCategories()->getUsers();      // Pour rechercher les utilisateurs inscrits à chacune des catégories
    
       // Faire une boucle pour envoyer un mail à chaque utilisateurs inscrit
       foreach($users as $users){

            if($users->getIsvalid()){

                $email = (new TemplatedEmail())
                    ->from('etrefouetsage@gmail.com')
                    ->to($users->getEmail())
                    ->subject($newsletters->getName())
                    ->htmlTemplate('email/news.html.twig')
                    ->context(compact('newsletters', 'users'))
                ;
                
                $mailer->send($email);
            }
       }

       return $this->redirectToRoute('app_newsletters_list');
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION pour supprimer les newsletters

    #[Route('/newsletters/{id}/delete', name: 'delete_newsletters')]                    // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name

    public function delete(Newsletters $newsletters, EntityManagerInterface $entityManager): Response   
    {                                                                                   // Créer une fonction delete() dans le controller pour supprimer un newsletters            
        $entityManager->remove($newsletters);                                           // Supprime une newsletters
        $entityManager->flush();                                                        // Exécute l'action DANS LA BDD

        $this->addFlash(                                                                // Envoyer une notification
            'success',
            'Supprimé avec succès!'
        );

        return $this->redirectToRoute('app_newsletters_list');                               // Rediriger vers la liste des newsletterss
    }
    
 
}