<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CoursController extends AbstractController
{
    #[Route('/cours', name: 'app_cours')]
    public function cours(): Response
    {
        return $this->render('cours/cours.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }

    #[Route('/facile', name: 'app_cours_facile')]
    public function coursfacile(): Response
    {
        return $this->render('cours/Facile/facile.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }

    #[Route('/intermediaire', name: 'app_cours_intermediaire')]
    public function coursintermediaire(): Response
    {
        return $this->render('cours/Intermediaire/intermediaire.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }


    #[Route('/difficile', name: 'app_cours_difficile')]
    public function coursdifficile(): Response
    {
        return $this->render('cours/Difficile/difficile.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }


    #[Route('/ordinateur&composants', name: 'app_cours_1_ordinateur_composants')]
    public function index(UserRepository $userRepository, UserInterface $user): Response
    {
        $user = $userRepository->find($this->getUser());

        if ($user->isSub()) {
            return $this->render('cours/Facile/ordinateur_composants/index.html.twig', [
                'controller_name' => 'CoursController',
            ]);
        } else {
            return $this->redirectToRoute('app_abonnement');
            }
    }

}