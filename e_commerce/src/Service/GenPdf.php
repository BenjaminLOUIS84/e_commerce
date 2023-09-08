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
        $pdfOptions->setPaper('A4', 'portrait');    // Paramétrer la taille et l'orientation
        
        // $domPdf = new Dompdf($options); Equivaut à ci-dessous
        $this->domPdf->setOptions($pdfOptions);

    }

    public function showPdfFile($html) {            // Pour afficher le pdf

        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->stream('facture.pdf', [
            'Attachement' => false
        ]);

    }

    public function generateBinaryPDF($html) {        

        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->output();

    }

}

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