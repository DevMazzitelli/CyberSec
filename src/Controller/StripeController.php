<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\BillingPortal\Session;
use Stripe\Exception\ApiErrorException;

class StripeController extends AbstractController
{
    #[Route('/create-portal-session', name: 'create_portal_session', methods: ['POST'])]
    public function createPortalSession(Request $request): Response
    {
        // Initialiser la bibliothèque Stripe avec votre clé secrète
        Stripe::setApiKey('sk_test_51PLk3mIJ9fc0Tr2fVamdYYxaFeQPqNdj4aMhwiOAwCcVPnUyEaKdG0rbh1dUJVKDCElqLE8HvYI42Vo1qjugSHCx001qHVp9O1');

        // ID de l'utilisateur connecté (par exemple, obtenu à partir de l'authentification)
        $user = $this->getUser();

        if (!$user || !$user->getStripeCustomerId()) {
            // Gestion des erreurs si l'utilisateur n'est pas connecté ou n'a pas de StripeCustomerId
            $this->addFlash('error', 'Utilisateur non valide ou ID client Stripe manquant.');
            return $this->redirectToRoute('app_profile');
        }

        $stripeCustomerId = $user->getStripeCustomerId();

        try {
            // Créer une session de portail de facturation
            $session = Session::create([
                'customer' => $stripeCustomerId,
                'return_url' => $this->generateUrl('app_home', [], true), // URL de retour après la gestion du portail
            ]);

            return $this->redirect($session->url);
        } catch (ApiErrorException $e) {
            // Gestion des erreurs de Stripe
            $this->addFlash('error', 'Erreur lors de la création de la session de portail : ' . $e->getMessage());
            return $this->redirectToRoute('app_profile');
        }
    }
}
