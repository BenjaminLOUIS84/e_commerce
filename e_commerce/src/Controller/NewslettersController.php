<?php

namespace App\Controller;

use App\Entity\Newsletters\Users;
use App\Form\NewslettersUsersType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

}
