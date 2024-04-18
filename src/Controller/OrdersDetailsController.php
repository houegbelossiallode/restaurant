<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Repository\OrdersDetailsRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdersDetailsController extends AbstractController
{
    #[Route('/orders/details/{id}', name: 'app_orders_details')]
    public function index(OrdersDetailsRepository $ordersDetailsRepository, int $id): Response
    {
        $details = new OrdersDetails();
        $commande = $ordersDetailsRepository->find($id); 
        $commandes = $ordersDetailsRepository->findByCommande($id);
        
    return $this->render('orders_details/index.html.twig', [
        'commandes' => $commandes
    ]);
}





    
}