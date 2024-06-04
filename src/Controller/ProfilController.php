<?php

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
    ): Response {

        return $this->render('profil/modification_abonnement.html.twig', [
        ]);
    }
}
