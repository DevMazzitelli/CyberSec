<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubscribsController extends AbstractController
{
    #[Route('/abonnement', name: 'app_abonnement')]
    public function index(): Response
    {
        return $this->render('subscribs/index.html.twig', [
            'controller_name' => 'SubscribsController',
        ]);
    }
}
