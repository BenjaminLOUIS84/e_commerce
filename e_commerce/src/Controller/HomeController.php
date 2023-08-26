<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
           
        ]);
        
        // Régler le PB de redirection vers les ancres //

        // $router->generateUrl('app_home') . '#team';
        // $this->redirect('@routename?id='.$id.'#team');
        // $url = $this->generateUrl('app_home', array('_fragment' => 'team'));
        // return $this->redirect($this->generateUrl('app_home').'#team');
        // $this->redirectToRoute('app_home', ['_fragment' => 'team']);

    }
    
}
