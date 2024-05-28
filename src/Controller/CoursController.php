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
    #[Route('/cours_ordinateur_composants', name: 'app_cours_1_ordinateur_composants')]
    public function index(UserRepository $userRepository, UserInterface $user): Response
    {
        $user = $userRepository->find($this->getUser());

        if ($user->isSub()) {
            return $this->render('cours/ordinateur_composants/index.html.twig', [
                'controller_name' => 'CoursController',
            ]);
        } else {
            return $this->redirectToRoute('app_abonnement');
            }
    }
}
