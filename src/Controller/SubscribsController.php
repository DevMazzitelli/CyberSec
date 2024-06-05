<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Webhook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    public function index(Security $security): Response
    {
        return $this->render('subscribs/index.html.twig', [
            'controller_name' => 'SubscribsController',
            'stripe_public_key' => $_ENV['STRIPE_PUBLIC_KEY'],
            ]);
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/create_checkout-session', name: 'app_create_checkout_abonnement_un')]
    public function createCheckoutSession(Request $request)
    {
        // Vérifie si l'utilisateur est connecté
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
            return $this->redirectToRoute('app_login');
        }

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
                'success_url' => $this->generateUrl('payment_success_abonnement_un', ['session_id' => '{CHECKOUT_SESSION_ID}'], UrlGeneratorInterface::ABSOLUTE_URL),
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
    #[Route('/create_checkout-session-deux', name: 'app_create_checkout_abonnement_deux')]
    public function createCheckoutSessionDeux(Request $request)
    {
        // Vérifie si l'utilisateur est connecté
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
            return $this->redirectToRoute('app_login');
        }

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
                            'name' => 'Abonnement Deux'
                        ],
                        // On met le prix en centimes donc *100
                        'unit_amount' => 1499,
                        'recurring' => [
                            'interval' => 'month'
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $this->generateUrl('payment_success_abonnement_deux', ['session_id' => '{CHECKOUT_SESSION_ID}'], UrlGeneratorInterface::ABSOLUTE_URL),
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
    #[Route('/create_checkout-session-trois', name: 'app_create_checkout_abonnement_trois')]
    public function createCheckoutSessionTrois(Request $request)
    {
        // Vérifie si l'utilisateur est connecté
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
            return $this->redirectToRoute('app_login');
        }

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
                            'name' => 'Abonnement Trois'
                        ],
                        // On met le prix en centimes donc *100
                        'unit_amount' => 2999,
                        'recurring' => [
                            'interval' => 'month'
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $this->generateUrl('payment_success_abonnement_trois', ['session_id' => '{CHECKOUT_SESSION_ID}'], UrlGeneratorInterface::ABSOLUTE_URL),
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
    #[Route('/payment-success-abonnement-un', name: 'payment_success_abonnement_un')]
    public function paymentSuccessAbonnementUn(Request $request)
    {
        // Récupère notre utilisateur
        $user = $this->getUser();

        // On va changer son abonnement de is_sub à true.
        $user->setIsSub(1);

        // On met la date actuel + 30 jours à is_sub_time_end
        $date = new \DateTime();
        $date->modify('+30 days');
        $user->setIsSubTimeEnd($date);
        $user->setIsSubTimeEnd2(null);
        $user->setIsSubTimeEnd3(null);

        // Stocker l'ID de l'abonnement Stripe dans l'utilisateur

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->render('subscribs/success.html.twig');
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/payment-success-abonnement-deux', name: 'payment_success_abonnement_deux')]
    public function paymentSuccessAbonnementDeux()
    {
        // Récupère notre utilisateur
        $user = $this->getUser();

        // On va changer son abonnement de is_sub à true.
        $user->setIsSub(2);

        // On met la date actuel + 30 jours à is_sub_time_end
        $date = new \DateTime();
        $date->modify('+30 days');
        $user->setIsSubTimeEnd2($date);
        $user->setIsSubTimeEnd3(null);
        $user->setIsSubTimeEnd(null);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->render('subscribs/success.html.twig');
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/payment-success-abonnement-trois', name: 'payment_success_abonnement_trois')]
    public function paymentSuccessTrois()
    {
        // Récupère notre utilisateur
        $user = $this->getUser();

        // On va changer son abonnement de is_sub à true.
        $user->setIsSub(3);

        // On met la date actuel + 30 jours à is_sub_time_end
        $date = new \DateTime();
        $date->modify('+30 days');
        $user->setIsSubTimeEnd3($date);
        // On enlève les dates de setIsSubTimeEnd et setIsSubTimeEnd2
        $user->setIsSubTimeEnd(null);
        $user->setIsSubTimeEnd2(null);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->render('subscribs/success.html.twig');
    }

    #[Route('/payment-cancel', name: 'payment_cancel')]
    public function paymentCancel()
    {
        $this->addFlash('notification', "Paiement échoué, vous avez été renvoyé vers la page d'abonnement");

        return $this->render('subscribs/cancel.html.twig');
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/unsubscribe', name: 'app_unsubscribe')]
    public function unsubscribe(): Response
    {
        // Récupère notre utilisateur
        $user = $this->getUser();

        // On va changer son abonnement de is_sub à 0.
        $user->setIsSub(0);

        // Annuler l'abonnement côté Stripe
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
        $subscription = \Stripe\Subscription::retrieve($user->getStripeSubscriptionId());
        $subscription->cancel();

        // Supprimer l'ID de l'abonnement Stripe de l'utilisateur
        $user->setStripeSubscriptionId(null);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Redirige vers une page de succès
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/webhook/payment/cancelled', name: 'webhook_cancel', methods: ['DELETE'])]
    public function handleWebhookCancelled(Request $request): Response
    {
        $payload = json_decode($request->getContent(), true);

        if ($payload['type'] === 'checkout.session.cancelled') {
            $session = $payload['data']['object'];

            // Récupérer l'ID de l'utilisateur à partir des métadonnées de la session de paiement
            $userId = $session['metadata']['user_id'];

            // Récupérer l'utilisateur à partir de l'ID
            $user = $this->entityManager->getRepository(User::class)->find($userId);

            // Mettre à jour l'abonnement de l'utilisateur dans votre base de données
            $user->setIsSub(0);
            $user->setStripeSubscriptionId(null);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return new Response('Received event', 200);
    }

    #[Route('/webhook/payment/succeeded', name: 'webhook_success', methods: ['POST'])]
    public function handleWebhook(Request $request): Response
    {
        $payload = json_decode($request->getContent(), true);

        if ($payload['type'] === 'checkout.session.completed') {
            $session = $payload['data']['object'];

            // Récupérer l'ID de l'utilisateur à partir des métadonnées de la session de paiement
            $userId = $session['metadata']['user_id'];

            // Récupérer l'utilisateur à partir de l'ID
            $user = $this->entityManager->getRepository(User::class)->find($userId);

            // Récupérer l'ID de l'abonnement à partir de la session
            $stripeSubscriptionId = $session['subscription'];

            // Mettre à jour l'abonnement de l'utilisateur dans votre base de données
            $user->setIsSub(1);
            $user->setStripeSubscriptionId($stripeSubscriptionId);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return new Response('Received event', 200);
    }
}
