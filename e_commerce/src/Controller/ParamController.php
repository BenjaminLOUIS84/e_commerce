<?php

namespace App\Controller;

use App\Entity\Newsletters\Users;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/param', name: 'app_param_')]
class ParamController extends AbstractController
{
    #[Route('/param', name: 'param')]
    public function index(): Response
    {
        return $this->render('param/index.html.twig', [
            'controller_name' => 'ParamController'
        ]);
    }
    // //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // // FONCTION POUR AFFICHER LA LISTE DE TOUS LES UTILISATEURS

    #[Route('/liste', name: 'liste')]
    public function liste(UserRepository $userRepository)
    { 
        if (!$this->isGranted('ROLE_ADMIN')) {                              // Permet d'empécher l'accès à cette action si ce n'est pas un admin
            throw $this->createAccessDeniedException('Accès non autorisé');
        }
        
        $users = $userRepository->findBy([], ["Pseudo" => "ASC"]);      // Affiche tous les utilisateurs

        return $this->render('param/liste.html.twig', compact('users'));
    }
    
}
