<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Form\UserModificationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    // adresse de livraison
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/profil/adresse', name: 'app_profil_adresse')]
    public function adresse(
        Request $request,
        Security $security,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
    ): Response {

        // Récupération de l'utilisateur
        $user = $security->getUser();

        // Fetch the existing address of the user
        $address = $user->getAddress();

        // If the user doesn't have an address, create a new one
        if (!$address) {
            $address = new Address();
        }

        // Création du formulaire
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        // Traitement du formulaire
        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Ajout de l'utilisateur à l'adresse
            $address->setUser($user);

            // Ajout de l'adresse à l'utilisateur
            $user->setAddress($address);

            // Enregistrement de l'adresse et de l'utilisateur
            $entityManager->persist($address);
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirection
            $this->addFlash('success', 'Votre adresse a bien été enregistrée');
            return $this->redirectToRoute('app_abonnement');
        }

        return $this->render('profil/address.html.twig', [
            'infoUser' => $userRepository->find($user->getId()),
            'form' => $form->createView(),
        ]);
    }
}
