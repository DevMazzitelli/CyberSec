<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LessonsController extends AbstractController
{
    #[Route('/lessons', name: 'app_lessons')]
    public function index(): Response
    {
        return $this->render('lessons/index.html.twig', [
            'controller_name' => 'LessonsController',
        ]);
    }
}
