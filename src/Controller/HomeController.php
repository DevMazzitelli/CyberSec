<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MessageRepository $messageRepository): Response
    {
        // Récupère la première annonce
        $message = $messageRepository->findOneBy([]);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'annonce' => $message ? $message->getAnnonce() : 'Aucune annonce disponible',
        ]);
    }

    #[Route('/faq', name: 'app_faq')]
    public function faq(): Response
    {
        return $this->render('home/faq.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
