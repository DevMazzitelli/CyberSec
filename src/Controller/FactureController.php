<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use App\Repository\UserRepository;
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
        int $id_commande
    ): Response
    {
        $infoUser = $userRepository->find($security->getUser()->getId());
        $order = $ordersRepository->find($id_commande);
        // Récupération de l'adresse lié à l'utilisateur
        $address = $infoUser->getAddress();

        $htmlContent = $this->renderView('emails/facture/facture.html.twig', [
            'infoUser' => $infoUser,
            'order' => $order,
            'address' => $address
        ]);

        $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true, 'isRemoteEnabled' => true]);
        $dompdf->loadHtml($htmlContent);
        $dompdf->setBasePath(realpath($this->getParameter('kernel.project_dir')).'/public');
        $dompdf->render();

        $response = new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="facture.pdf"'
        ]);

        return $response;
    }
}
