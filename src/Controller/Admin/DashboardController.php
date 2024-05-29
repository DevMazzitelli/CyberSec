<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(UserCrudController::class)->generateUrl();

       return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('CyberSec');
    }

    public function configureMenuItems(): iterable
    {
        // Ajout de catégorie
        yield MenuItem::section('Redirection');
        // Revenir à l'accueil de mon dashboard
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::section('Message');
        // Ajout du lien vers le dashboard "Contact"
        yield MenuItem::linkToCrud('Contacts', 'fa fa-address-book', Contact::class);




        // Création de linkToDashboard contact
        // Création du CRUD contact avec make:admin:crud
        // Dans le crud, faire le configureFields
        // Faire en sorte que lorsque je rajoute un contact, on l'enregistre en BDD pour le récupérer dans le panel d'admin.

    }
}
