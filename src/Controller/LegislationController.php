<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegislationController extends AbstractController
{
    #[Route('/mentions_légales', name: 'app_mentions')]
    public function index(): Response
    {
        return $this->render('legislation/mentions.html.twig', [
            'controller_name' => 'LegislationController',
        ]);
    }

    #[Route('/politique_de_confidentialité', name: 'app_politique')]
    public function politique(): Response
    {
        return $this->render('legislation/politique.html.twig', [
            'controller_name' => 'LegislationController',
        ]);
    }
}
