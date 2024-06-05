<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password
            $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()))
                ->setRoles(['ROLE_USER'])
                ->setIsSub(false);

            // Create a new instance of Address
            $address = new Address();
            $address->setRue($form->get('address')->get('rue')->getData())
                ->setVille($form->get('address')->get('ville')->getData())
                ->setPays($form->get('address')->get('pays')->getData())
                ->setCodePostal($form->get('address')->get('codePostal')->getData())
                ->setAdresse($form->get('address')->get('adresse')->getData())
                ->setTest($form->get('address')->get('test')->getData());

            // Set the user for the address and vice versa
            $address->setUser($user);
            $user->addAddress($address);

            // Persist and flush the entities
            $entityManager->persist($user);
            $entityManager->persist($address);
            $entityManager->flush();

            // Send confirmation email
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('contact@cybersec.com', 'CyberSec'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // Redirect to home after successful registration
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            // Get the user ID from the request parameters
            $userId = $request->query->get('id');

            // Find the user by ID
            $user = $entityManager->getRepository(User::class)->find($userId);

            // Check if the user exists
            if (!$user) {
                throw new \Exception('User not found');
            }

            // Validate the email confirmation link
            $this->emailVerifier->handleEmailConfirmation($request, $user);

            // Set is_verified to true
            $user->setVerified(true);

            // Persist the changes
            $entityManager->flush();

            $this->addFlash('success', 'Your email address has been verified.');

            return $this->redirectToRoute('app_home');
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }
    }
}
