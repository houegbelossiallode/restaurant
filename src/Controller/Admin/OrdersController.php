<?php

namespace App\Controller\Admin;

use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Entity\User;
use App\Repository\OrdersRepository;
use App\Repository\PlatRepository;
use App\Services\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrdersController extends AbstractController
{
    #[Route('/commandes/ajout', name: 'commandes_add')]
    public function add(SessionInterface $session, PlatRepository $platRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');


        $panier = $session->get('panier', []);

        if($panier === []){
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('app_home');
        }

        //Le panier n'est pas vide, on crée la commande
        $order = new Orders;

        // On remplit la commande
        $order->setUsers($this->getUser());
        $order->setReference(uniqid());

        // On parcourt le panier pour créer les détails de commande
        foreach($panier as $item => $quantity){
            $orderDetails = new OrdersDetails();

            // On va chercher le produit
            $plat = $platRepository->find($item);
            
            $price = $plat->getPrix() * $quantity;

            // On crée le détail de commande
            $orderDetails->setPlat($plat);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            $order->addOrdersDetail($orderDetails);
        }

        // On persiste et on flush
        $em->persist($order);
        $em->flush();

        $session->remove('panier');

       $this->addFlash('message', 'commande créee avec succès');
       return $this->redirectToRoute('app_home');
       
    }





    #[Route('/orders', name: 'app_orders')]
    public function detail(Request $request,OrdersRepository $ordersRepository): Response
    {
    
    $usersId= $this->getUser() ;
    
    $orders = $ordersRepository->findByUser($usersId);
    
    
    return $this->render('orders/index.html.twig', [
        'orders' => $orders
    ]);
}


#[Route('/orders/listes', name: 'app_orders_listes')]
public function listes(Request $request,OrdersRepository $ordersRepository, PdfService $pdf,int $id)
{
    
$usersId= $this->getUser() ;

$orders = $ordersRepository->findByUser($usersId);


$html = $this->renderView('orders/liste.html.twig', [
    'orders' => $orders,
    
]);
$pdf->showPdfFile($html);
}










    
}