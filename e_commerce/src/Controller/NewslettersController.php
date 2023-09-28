<?php

namespace App\Controller;

use App\Entity\Newsletters\Users;
use App\Form\NewslettersUsersType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/newsletters', name: 'app_newsletters_')]
class NewslettersController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        $form = $this->createForm(NewslettersUsersType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {             // Si le formulaire soumis est valide alors
       
            $token = hash('sha256', uniqid());
            $user->setValidationToken($token);
        

            $entityManager->persist($user);
            $entityManager->flush();
        
            $this->addFlash('message', 'Inscription en attente de validation');
            return $this->redirectToRoute('app_home');
        }

        
        return $this->render('newsletters/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
