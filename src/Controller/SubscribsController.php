<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
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
    public function index(): Response
    {
        return $this->render('subscribs/index.html.twig', [
            'controller_name' => 'SubscribsController',
            'stripe_public_key' => $_ENV['STRIPE_PUBLIC_KEY'],
        ]);
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/abonnement_Un', name: 'app_abonnementUn')]
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
    #[Route('/abonnement_Deux', name: 'app_abonnementDeux')]
    public function createCheckoutSession2(Request $request)
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
    #[Route('/abonnement_Trois', name: 'app_abonnementTrois')]
    public function createCheckoutSession3(Request $request)
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
        $this->addFlash('notification', "Paiement échoué, vous avez été renvoyé vers la page d'abonnement");

        return $this->render('subscribs/cancel.html.twig');
    }

    /**
     * @Route("/subscription/cancel", name="subscription_cancel")
     */
    public function cancelSubscription(): Response
    {
        $user = $this->getUser();
        $subscription = $this->em->getRepository(Subscription::class)->findOneBy(['user' => $user]);

        if (!$subscription) {
            return $this->redirectToRoute('home'); // ou autre page de votre choix
        }

        Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        $stripeSubscription = StripeSubscription::retrieve($subscription->getStripeSubscriptionId());
        $stripeSubscription->cancel();

        $subscription->setStatus('canceled');
        $this->em->flush();

        return $this->redirectToRoute('subscription_status'); // ou autre page de votre choix
    }



    /**
     * @Route("/subscription/change", name="subscription_change")
     */
    public function changeSubscription(Request $request): Response
    {
        $user = $this->getUser();
        $newPlan = $request->query->get('plan'); // ou via POST

        $subscription = $this->em->getRepository(Subscription::class)->findOneBy(['user' => $user]);

        if (!$subscription) {
            return $this->redirectToRoute('home'); // ou autre page de votre choix
        }

        Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        $stripeSubscription = StripeSubscription::retrieve($subscription->getStripeSubscriptionId());
        $stripeSubscription->items = [
            [
                'id' => $stripeSubscription->items->data[0]->id,
                'price' => $newPlan,
            ],
        ];
        $stripeSubscription->save();

        $subscription->setPlan($newPlan);
        $this->em->flush();

        return $this->redirectToRoute('subscription_status'); // ou autre page de votre choix
    }











}

