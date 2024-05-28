<?php

// src/Controller/ContactController.php
namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface; // Importe l'EntityManagerInterface

class ContactController extends AbstractController
{
    private $entityManager;

    // Injecte l'EntityManagerInterface via le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Utilise l'EntityManager injecté
            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            // Envoie de l'email
            $this->sendEmail($contact, $mailer);

            $this->addFlash('notification', 'Votre message a été envoyé avec succès.');

            return $this->redirectToRoute('app_home'); // Redirige vers la page d'accueil
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function sendEmail(Contact $contact, MailerInterface $mailer)
    {
        $email = (new Email())
            ->from($contact->getEmail())
            ->to('rayanasa77@gmail.com') // Remplacez par votre adresse email
            ->html($this->renderView('emails/contact.html.twig', ['contact' => $contact]));

        $mailer->send($email);
    }
}
