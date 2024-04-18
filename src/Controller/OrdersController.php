<?php

namespace App\Controller;

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
use Dompdf\Dompdf;
use Dompdf\Options;

class OrdersController extends AbstractController
{
    #[Route('/commandes/ajout', name: 'commander')]
    public function add(SessionInterface $session, PlatRepository $platRepository, EntityManagerInterface $em): Response
    {
       


        $panier = $session->get('panier', []);

        if($panier === []){
            $this->addFlash('message', 'Votre panier est vide');
           return $this->redirectToRoute('app_orders');
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
     return $this->redirectToRoute('app_cart');
       
    }





    #[Route('/orders', name: 'app_orders')]
    public function detail(Request $request,OrdersRepository $ordersRepository)
    {
        
    $usersId= $this->getUser() ;
    
    $orders = $ordersRepository->findByUser($usersId);
    
    
    return $this->render('orders/index.html.twig', [
        'orders' => $orders,
        
    ]);
   
}

 
#[Route('/orders/liste', name: 'app_orders_liste')]
public function listes(Request $request,OrdersRepository $ordersRepository, PdfService $pdf)
{
    
$usersId= $this->getUser() ;

$orders = $ordersRepository->findByUser($usersId);


$html = $this->render('orders/liste.html.twig', [
    'orders' => $orders,
    
]);
$dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
$pdf->showPdfFile($html);

}




#[Route('orders/delete/{id}', name: 'app_delete')]
public function  delete(int $id, ManagerRegistry $doctrine,Request $request) : Response 
{

$orders = new Orders();
 
 $orders = $doctrine->getRepository(Orders::class)->find($id);

 if($orders)
 {
     $manager = $doctrine->getManager();
     $manager->remove($orders);
     $manager->flush();
     $this->addFlash('success',"Supprimer avec succès");
    
     return $this->redirectToRoute('app_orders');
    

     
 }else{
     $this->addFlash('error','Commande inexistante');
 }

   return $this->redirectToRoute('app_orders');


 
 }



#[Route('/pdf/generator', name: 'app_pdf_generator')]
    public function pdf(): Response
    {
        // return $this->render('pdf_generator/index.html.twig', [
        //     'controller_name' => 'PdfGeneratorController',
        // ]);
        $data = [
            'imageSrc'  => $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/assets/images/f5.png'),
            'name'         => 'John Doe',
            'address'      => 'USA',
            'mobileNumber' => '000000000',
            'email'        => 'john.doe@email.com'
        ];
        $html =  $this->renderView('orders/pdf.html.twig', $data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
         
        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
 
    private function imageToBase64($path) {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }












}