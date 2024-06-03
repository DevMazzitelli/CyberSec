<?php

// src/Controller/ProfilController.php

namespace App\Controller;

use App\Form\UserModificationType;
use App\Form\EditSubscribsType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Stripe\Stripe;
use Stripe\Subscription;
use Stripe\Exception\ApiErrorException;

class ProfilController extends AbstractController
{
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/profil', name: 'app_profile')]
    public function profile(UserRepository $userRepository): Response
    {
        $infoUser = $this->getUser();

        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'infoUser' => $infoUser,
        ]);
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/profil/modification', name: 'app_profil_modification')]
    public function modifier(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        Request $request
    ): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserModificationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if (!empty($plainPassword)) {
                $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profil/modification.html.twig', [
            'modificationProfil' => $form->createView(),
        ]);
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/profil/modification_abonnement', name: 'app_profil_modifabonnement')]
    public function modifAbonnement(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $user = $this->getUser();
        $form = $this->createForm(EditSubscribsType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si la case est cochée, la valeur de is_sub doit être mise à false pour résilier l'abonnement
            if ($form->get('is_sub')->getData()) {
                $user->setIsSub(false);

                // Initialiser la bibliothèque Stripe avec votre clé secrète
                Stripe::setApiKey('votre_cle_secrete_stripe');

                // L'utilisateur peut se désabonner
                try {
                    // Récupérer l'ID de l'abonnement Stripe de l'utilisateur
                    $subscriptionId = $user->getStripeSubscriptionId();

                    // Annuler l'abonnement sur Stripe
                    $subscription = Subscription::retrieve($subscriptionId);
                    $subscription->cancel();

                    // Persister les modifications dans votre base de données
                    $entityManager->persist($user);
                    $entityManager->flush();

                } catch (ApiErrorException $e) {
                    // Gérer les erreurs de Stripe
                    $this->addFlash('error', 'Erreur lors de la résiliation de l\'abonnement : ' . $e->getMessage());
                } catch (\Exception $e) {
                    // Gérer les autres erreurs
                    $this->addFlash('error', 'Erreur inattendue : ' . $e->getMessage());
                }
            } else {
                // Persister les modifications dans votre base de données si nécessaire
                $entityManager->persist($user);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->render('profil/modifAbonnement.html.twig', [
            'modificationProfil' => $form->createView(),
        ]);
    }
}
