<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Service\GenPdf;
use App\Entity\Commande;
use App\Entity\CommandeLivre;
use App\Service\SendMailService;
use App\Repository\UserRepository;
use App\Repository\FactureRepository;
use App\Form\ResetPasswordRequestType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandeLivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

#[Route('/facture', name: 'app_facture_')]  
class FactureController extends AbstractController
{

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR METTRE LA COMMANDE EN FACTURE

    #[Route('/ajout/{id}', name: 'add')]
    public function add(

        SessionInterface $session,
        EntityManagerInterface $em,
        Commande $commande,
        CommandeRepository $commandeRepository,

        ): Response  
    {

        $commande->getId();                         // Pour récupérer la commande 
        // dd($commande);

        $facture = new Facture();                   // Créer une facture
        $facture->setNumeroFacture(uniqid());       // Instencier le numéro de facture
        $facture->setCommande($commande);           // Lier la commande à la facture

        // dd($facture);

        $em->persist($facture);
        $em->flush();
        
        $this->addFlash(                            // Envoyer une notification
            'success',
            'Facture ajoutée avec succès!'
        );

        return $this->redirectToRoute('app_facture_detail_facture', ['id' => $commande->getId ()], Response::HTTP_SEE_OTHER); // Redirige vers le détail de la facture
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR GENERER LE FICHIER PDF DE CHAQUE FACTURES

    #[Route('/pdf/{id}', name: 'facture_pdf')]
    public function generatePdfFacture(

        Facture $facture = null,
        GenPdf $pdf,
        Commande $commande,
        FactureRepository $factureRepository,
        CommandeLivreRepository $commandeLivreRepository,
        CommandeRepository $commandeRepository
        )

    {
        $commandes = $commandeRepository->findBy([], ["numero_commande" => "ASC"]);
        $factures = $factureRepository->findBy([], ["numero_facture" => "ASC"]);
        $commandeLivres = $commandeLivreRepository->findAll();

        $html = $this->render('facture/facture.html.twig', [
            'facture' => $facture,
            'commande' => $commande,
            'commandeLivres' => $commandeLivres,
            'commandes' => $commandes,
            'factures' => $factures
        ]);

        $pdf->showPdfFile($html);

    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE DETAIL DE CHAQUE FACTURES

    #[Route('/facture/{id}/detail', name: 'detail_facture')]       // Reprendre la route en ajoutant /detail à l'URL et en changeant le nom du name
    public function detail(

        Commande $commande,
        FactureRepository $factureRepository,
        CommandeLivreRepository $commandeLivreRepository,
        CommandeRepository $commandeRepository,
        

        ): Response
    {                                                            // Créer une fonction detail() dans le controller pour afficher le détail d'une facture 
        $commandes = $commandeRepository->findBy([], ["numero_commande" => "ASC"]);
        $factures = $factureRepository->findBy([], ["numero_facture" => "ASC"]);
        $commandeLivres = $commandeLivreRepository->findAll();
        
        return $this->render('facture/detail.html.twig', [          // Pour faire le lien entre le controller et la vue detail.html.twig (il faut donc la créer dans le dossier facture)
            'commande' => $commande,
            'factures' => $factures,
            'commandeLivres' => $commandeLivres,
            'commandes' => $commandes,
            

        ]);
    }

    // Fonction pour afficher une notification pour prévenir le client que sa commande sera bientôt disponible
    // et envoyer un mail pour avertir le client que l'édition numérique est disponible en téléchargement

    #[Route('/', name: 'notif')]
    public function notif(
        Request $request,
        UserRepository $userRepository,
        // TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,

        //Créer un service pour envoyer des mail ou utiliser mailHog
        SendMailService $mail

    ): Response

    {
        $form = $this->createForm(ResetPasswordRequestType::class);         // Récupérer le formulaire
        
        $form->handleRequest($request);                                     // Pour traiter le formulaire

        if($form->isSubmitted() && $form->isvalid()){                       // Pour vérifier si le formulaire est valide et soumis
            
            // On va chercher l'utilisateur par son email
            $user = $userRepository->findOneByEmail($form->get('email')->getData());    // Pour chercher les données dans l'email qui est inscrit dans le formulaire
        
            // On vérifie si on un utilisateur
            if($user){

                // On génère un token de réinitialisation
                //$token = $tokenGenerator->generateToken();
                //$user->setResetToken($token);

                //$entityManager->persist($user);                             // Pour gérer le traitement en BDD
                //$entityManager->flush();                                    
            
                // On génère un lien de réinitialisation du mot de passe
                //$url = $this->generateUrl('reset_pass', ['token' => $token],
                //UrlGeneratorInterface::ABSOLUTE_URL);                       // Permet de générer l'url pour utiliser la nouvelle route pour créer un nouveau mot de passe

                // On créer les données du mail
                $context = compact('user');

                // Envoi du mail (Utiliser le service mail)
                $mail->send(
                    'etrefouetsage@gmail.com',                                  // Emetteur
                    $user->getEmail(),                                          // Destinataire
                    'Notification',                                             // Titre
                    'notif',                                                    // Template 
                  $context
                );

                $this->addFlash('success', 'Email envoyé avec succès');
                return $this->redirectToRoute('app_user');                 // Redirection vers l'espace personnel'

            }
            // Cas où $user est NULL
            $this->addFlash('danger', 'Un problème est survenu');           // En cas d'erreur on est redirigé vers l'espace personnel' et le message s'affichera dans cette page (*)
            return $this->redirectToRoute('app_user');
        }

        return $this->render('facture/notif.html.twig', [ // Passer le formulaire en arguement dans un tableau
            
            'notifForm' => $form->createView()                        // Demande pour créer le formulaire 'requestPassFrom' et pour afficher selui-ci dans une vue
        ]);  
    }

}
