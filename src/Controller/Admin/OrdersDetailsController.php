<?php

namespace App\Controller\Admin;


use App\Entity\OrdersDetails;
use App\Repository\OrdersDetailsRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdersDetailsController extends AbstractController
{
    #[Route('/admin/orders', name: 'admin_orders')]
    public function index(OrdersDetailsRepository $ordersDetailsRepository): Response
    {
     
    $commandes = $ordersDetailsRepository->findAll();
    return $this->render('admin/orders/index.html.twig', [
        'commandes' => $commandes
    ]);
}





    
}