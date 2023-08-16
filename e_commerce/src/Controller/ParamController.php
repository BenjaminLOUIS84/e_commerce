<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParamController extends AbstractController
{
    #[Route('/param', name: 'app_param')]
    public function index(): Response
    {
        return $this->render('param/index.html.twig', [
            'controller_name' => 'ParamController',
        ]);
    }
}
