<?php

namespace App\Controller;

use App\Entity\Newsletters\Users;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ParamController extends AbstractController
{
    #[Route('/param', name: 'app_param')]
    public function index(
        // Users $users
        ): Response
    {
        return $this->render('param/index.html.twig', [
            'controller_name' => 'ParamController',
            // 'users' => $users
        ]);
    }
}
