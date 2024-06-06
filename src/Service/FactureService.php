<?php

namespace App\Service;

use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class FactureService
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function generatePdf($infoUser, $order, $address, $projectDir)
    {
        $htmlContent = $this->twig->render('emails/facture/facture.html.twig', [
            'infoUser' => $infoUser,
            'order' => $order,
            'address' => $address
        ]);

        $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true, 'isRemoteEnabled' => true]);
        $dompdf->loadHtml($htmlContent);
        $dompdf->setBasePath(realpath($projectDir).'/public');
        $dompdf->render();

        // Return the PDF content as a string
        return $dompdf->output();
    }
}
