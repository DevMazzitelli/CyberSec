<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Invoice;

class FactureController extends AbstractController
{
    #[Route('/telecharger-facture/{id_commande}', name: 'telecharger_facture')]
    public function telechargerFacture(int $id_commande): Response
    {
        // Configurez la clé secrète de l'API Stripe
        Stripe::setApiKey('sk_test_51PLk3mIJ9fc0Tr2fVamdYYxaFeQPqNdj4aMhwiOAwCcVPnUyEaKdG0rbh1dUJVKDCElqLE8HvYI42Vo1qjugSHCx001qHVp9O1');

        // Récupérer la facture depuis Stripe en utilisant l'ID de la commande
        $facture = Invoice::retrieve($id_commande);

        // Récupérer le contenu de la facture (par exemple, le PDF) depuis Stripe
        $contenuFacture = $facture->pdf;

        // Renvoyer le contenu de la facture en tant que réponse HTTP avec le bon en-tête pour le rendre téléchargeable
        return new Response(
            $contenuFacture,
            Response::HTTP_OK,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="facture.pdf"'
            )
        );
    }
}
