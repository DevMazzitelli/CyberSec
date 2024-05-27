<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/faq', name: 'app_faq')]
    public function faq(): Response
    {
        return $this->render('home/faq.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/ml', name: 'app_ml')]
    public function ml(): Response
    {
        return $this->render('home/ml.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/politique', name: 'app_politique')]
    public function politique(): Response
    {
        return $this->render('home/politique.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/cgv', name: 'app_cgv')]
    public function cgv(): Response
    {
        return $this->render('home/cgv.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    #[Route('/abonnement', name: 'app_abonnement')]
    public function abonnement(): Response
    {
        return $this->render('home/abonnement.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

}
