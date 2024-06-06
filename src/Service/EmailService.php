<?php

// src/Service/EmailService.php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailService
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendInvoice($userEmail, $invoicePath)
    {
        $email = (new Email())
            ->from('no-reply@example.com')
            ->to($userEmail)
            ->subject('Votre facture')
            ->text('Veuillez trouver ci-joint votre facture.')
            ->attachFromPath($invoicePath);

        $this->mailer->send($email);
    }

    public function sendConfirmation($userEmail, $subscriptionDetails)
    {
        $email = (new Email())
            ->from('no-reply@example.com')
            ->to($userEmail)
            ->subject('Confirmation d’abonnement')
            ->html($this->twig->render('emails/confirmation.html.twig', ['details' => $subscriptionDetails]));

        $this->mailer->send($email);
    }

    public function sendEmailAfterSub($userEmail, $pdfContent)
    {
        $email = (new Email())
            ->from('cybersec@gmail.com')
            ->to($userEmail)
            ->subject('Votre facture d\'abonnement')
            ->text('Veuillez trouver ci-joint votre facture d\'abonnement.')
            ->html('<p>Veuillez trouver ci-joint votre facture d\'abonnement.</p>')
            ->attach($pdfContent, 'facture.pdf', 'application/pdf');

        $this->mailer->send($email);
    }
}
