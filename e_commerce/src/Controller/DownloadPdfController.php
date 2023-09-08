<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Dompdf\Dompdf;
use Dompdf\Options;

class DownloadPdfController extends AbstractController
{
    // #[Route('/download/pdf', name: 'app_download_pdf')]
    // public function index(): Response
    // {
    //     return $this->render('download_pdf/index.html.twig', [
    //         'controller_name' => 'DownloadPdfController',
    //     ]);
    // }

    #[Route('/download/pdf', name: 'app_download_pdf')]
    public function download(){

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Courier');

        $dompdf = new Dompdf($pdfOptions);

        
        $html = $this->renderView('download_pdf/index.html.twig', [
            'title' => 'Bonjour',
        ]);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        ob_get_clean(); // Pour permettre l'affichage ou le téléchargement

        $dompdf->stream("Test.pdf", [
            "Attachement" => true
        ]);
    }
}
