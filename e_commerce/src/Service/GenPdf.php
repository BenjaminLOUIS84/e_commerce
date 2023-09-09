<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class GenPdf
{
    private $domPdf;

    public function __construct() {

        $this->domPdf = new DomPdf();

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Courier'); // Paramétrer la police
        $pdfOptions->set('A4', 'portrait');      // Paramétrer la taille et l'orientation
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $this->domPdf->setOptions($pdfOptions);

    }

    /////////////////////////////////////////////////FONCTION POUR AFFICHER LA FACTURE EN PDF 

    public function showPdfFile($html) {            // Pour afficher le pdf

        $this->domPdf->loadHtml($html);             // Pour mettre du html dans le PDF
        $this->domPdf->render();                    // Pour générer le PDF
        ob_get_clean();                             // Pour permettre le téléchargement et l'affichage du fichier PDF

        $this->domPdf->stream('Facture.pdf', [
            'Attachement' => false                  // Pour ENVOYER le PDF
        ]);

    }

}

/////////////////////////////////////////////////AUTRE FONCTION
// public function generateBinaryPDF($html) {        

    //     $this->domPdf->loadHtml($html);
    //     $this->domPdf->render();
    //     $this->domPdf->output();

    // }

/////////////////////////////////////////////////DOCUMENTATION
// Instancier un objet de la class domPDF
// $dompdf = new Dompdf();

// Mettre du HTML dans le PDF
// $dompdf->loadHtml('Facture');

// (Optional) Setup the paper size and orientation Choisir le format et l'orientation
// $dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF Générer le PDF
// $dompdf->render();

// Output the generated PDF to Browser Autoriser le téléchargement
// $dompdf->stream();