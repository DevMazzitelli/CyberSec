<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

class CoursController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/cours', name: 'app_cours')]
    public function cours(): Response
    {
        return $this->render('cours/cours.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/facile', name: 'app_cours_facile')]
    public function coursfacile(): Response
    {
        $user = $this->security->getUser();
        // Récupère la date actuelle
        $now = new \DateTime();

        if (
            (!$user->getIsSubTimeEnd() || $user->getIsSubTimeEnd() < $now) &&
            (!$user->getIsSubTimeEnd2() || $user->getIsSubTimeEnd2() < $now) &&
            (!$user->getIsSubTimeEnd3() || $user->getIsSubTimeEnd3() < $now)
        ) {
            return $this->redirectToRoute('app_abonnement');
        }

        return $this->render('cours/Facile/facile.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/intermediaire', name: 'app_cours_intermediaire')]
    public function coursintermediaire(): Response
    {
        $user = $this->security->getUser();
        // Récupère la date actuelle
        $now = new \DateTime();

        if (
            (!$user->getIsSubTimeEnd2() || $user->getIsSubTimeEnd2() < $now) &&
            (!$user->getIsSubTimeEnd3() || $user->getIsSubTimeEnd3() < $now)
        ) {
            return $this->redirectToRoute('app_abonnement');
        }

        return $this->render('cours/Intermediaire/intermediaire.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }


    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/difficile', name: 'app_cours_difficile')]
    public function coursdifficile(): Response
    {
        $user = $this->security->getUser();
        // Récupère la date actuelle
        $now = new \DateTime();

        if (
            (!$user->getIsSubTimeEnd3() || $user->getIsSubTimeEnd3() < $now)
        ) {
            return $this->redirectToRoute('app_abonnement');
        }

        return $this->render('cours/Difficile/difficile.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }

    //cours/Facile/ordinateur_composants/index.html.twig
    #[Route('/ordinateur&composants', name: 'app_cours_1_ordinateur_composants')]
    public function index(): Response
    {
        $user = $this->security->getUser();
        // Récupère la date actuelle
        $now = new \DateTime();

        if (
            (!$user->getIsSubTimeEnd() || $user->getIsSubTimeEnd() < $now) &&
            (!$user->getIsSubTimeEnd2() || $user->getIsSubTimeEnd2() < $now) &&
            (!$user->getIsSubTimeEnd3() || $user->getIsSubTimeEnd3() < $now)
        ) {
            return $this->redirectToRoute('app_abonnement');
        }

        return $this->render('cours/Facile/ordinateur_composants/index.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }

    #[Route('/ordinateur&composants/2', name: 'app_cours_2_ordinateur_composants')] // Changement de nom de route ici
    public function index2(): Response
    {
        $user = $this->security->getUser();
        // Récupère la date actuelle
        $now = new \DateTime();

        if (
            (!$user->getIsSubTimeEnd() || $user->getIsSubTimeEnd() < $now) &&
            (!$user->getIsSubTimeEnd2() || $user->getIsSubTimeEnd2() < $now) &&
            (!$user->getIsSubTimeEnd3() || $user->getIsSubTimeEnd3() < $now)
        ) {
            return $this->redirectToRoute('app_abonnement');
        }

        return $this->render('cours/Facile/ordinateur_composants/partie2.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }






























}
