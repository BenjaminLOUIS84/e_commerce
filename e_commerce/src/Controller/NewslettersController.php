<?php

namespace App\Controller;

use App\Entity\Newsletters\Users;
use App\Form\NewslettersUsersType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/newsletters', name: 'app_newsletters_')]
class NewslettersController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $user = new Users();
        $form = $this->createForm(NewslettersUsersType::class, $user);
        
        return $this->render('newsletters/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
