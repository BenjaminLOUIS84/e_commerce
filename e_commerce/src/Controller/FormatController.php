<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormatController extends AbstractController
{
    #[Route('/format', name: 'app_format')]
    public function index(): Response
    {
        return $this->render('format/index.html.twig', [
            'controller_name' => 'FormatController',
        ]);
    }
}
