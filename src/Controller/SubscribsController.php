<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface; // Import correct
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // Utiliser Annotation\Route pour les annotations de route
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security; // Import correct pour Security
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted; // Import correct pour IsGranted

class SubscribsController extends AbstractController
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    #[Route('/abonnement', name: 'app_abonnement')]
    public function index(): Response
    {
        return $this->render('subscribs/index.html.twig', [
            'controller_name' => 'SubscribsController',
        ]);
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/create_checkout-session', name: 'app_create_checkout_session')]
    public function createCheckoutSession(Request $request)
    {
        try {
            // On initialise notre clef stripe
            \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
            // On récupère l'utilisateur
            $user = $this->getUser();

            // Création de notre checkout session
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Abonnement Un'
                        ],
                        // On met le prix en centimes donc *100
                        'unit_amount' => 699,
                        'recurring' => [
                            'interval' => 'month'
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'metadata' => [
                    'user_id' => $user->getId(),
                ],
            ]);

            return new JsonResponse(['id' => $session->id]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return new Response($e->getMessage(), 400);
        }
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/payment-success', name: 'payment_success')]
    public function paymentSuccess()
    {
        // Récupère notre utilisateur
        $user = $this->getUser();

        // On va changer son abonnement de is_sub à true.
        $user->setSub(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->render('subscribs/success.html.twig');
    }

    #[Route('/payment-cancel', name: 'payment_cancel')]
    public function paymentCancel()
    {
        return $this->render('subscribs/cancel.html.twig');
    }
}

