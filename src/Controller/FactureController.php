<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use App\Repository\UserRepository;
use App\Service\FactureService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Routing\Annotation\Route;


class FactureController extends AbstractController
{
    #[Route('/telecharger-facture/{id_commande}', name: 'telecharger_facture')]
    public function telechargerFacture(
        Security $security,
        UserRepository $userRepository,
        OrdersRepository $ordersRepository,
        FactureService $factureService,
        int $id_commande
    ): Response
    {
        $infoUser = $userRepository->find($security->getUser()->getId());
        $order = $ordersRepository->find($id_commande);
        // Récupération de l'adresse lié à l'utilisateur
        $address = $infoUser->getAddress();

        return $factureService->generatePdf($infoUser, $order, $address, $this->getParameter('kernel.project_dir'));
    }
}
