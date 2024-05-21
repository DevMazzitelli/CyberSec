<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;


class SecurityController extends AbstractController
{
    // Connexion sur notre site
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $lastUsername]);

        return $this->render('security/connexion.html.twig',
            ['last_username' => $lastUsername, 'error' => $error]);
    }

    // Vérifie si l'utilisateur est connecté. "IS_AUTHENTICATED_FULLY"
    // Système de déconnexion (Voir le security.yaml)
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
