<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {
        // Get the last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Find the user by email
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $lastUsername]);

        // Check if the user exists and is verified
        if ($user && !$user->isVerified()) {
            // Redirect user to a page indicating that their email is not verified
            return $this->redirectToRoute('app_home');
        }

        // Get the login error, if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // Render the login form with necessary data
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
