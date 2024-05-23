<?php

namespace App\Controller;

use App\Form\UserModificationType;
use App\Repository\UserRepository;
use Cassandra\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfilController extends AbstractController
{
    #[IsGranted("IS_AUTHENTICATED_FULLY")] // Autoriser quand l'utilisateur est connecté
    #[Route('/profil', name: 'app_profile')]
    public function profile(
        Security $security,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        // On récupère les informations de l'utilisateur (par id)
        // ID > On sait quel est l'utilisateur > on récupère les informations de l'utilisateur.
        $infoUser = $userRepository->find($security->getUser()->getId());

        return $this->render('profil/index.html.twig', [
            'controller_name' => 'HomeController',
            'infoUser' => $infoUser,
            // On rend à la vue nos informations.
        ]);
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")] // Autoriser quand l'utilisateur est connecté
    #[Route('/profil/modification', name: 'app_profil_modification')]
    public function modifier(
        EntityManagerInterface $entityManager, // Entity = User
        UserRepository $userRepository, // Permettra de modifier les informations
        UserInterface $user, // Sécurité HttpFoundation
        Request $request // Permet de faire des requêtes
    ) {
        // On récupère l'id de l'utilisateur
        $user = $userRepository->find($user->getId());
        // Créer notre formulaire
        $form = $this->createForm(UserModificationType::class, $user);
        // On va créer une requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Tu récupères le mot de passe et tu le hash

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profil/modification.html.twig', [
            'modificationProfil' => $form->createView(), // ERREUR : DOEST EXIST = Tu as oublié de rendre à la vue
        ]);
    }
}
