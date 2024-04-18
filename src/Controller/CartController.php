<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Entity\Payment;
use App\Form\PaymentType;
use App\Repository\PlatRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(PlatRepository $platRepository, SessionInterface $session): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class,$payment);
        
        $panier = $session->get('panier', []);

        // On initialise des variables
        $data = [];
        $total = 0;
        
        foreach($panier as $id => $quantity)
        {
           
            $plat = $platRepository->find($id);
            $data[] = [
                'plat' => $plat,
                'quantity' => $quantity
            ];
            
            $total += $plat->getPrix() * $quantity;
            
        }
        $count = COUNT($data);
        
        return $this->render('cart/index.html.twig', [
            'data' => $data,
             'total' => $total,
             'count' => $count,
             'form' => $form->createView(),
        ]);
    }



    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(SessionInterface $session, int $id)
    {
        
        //On récupère l'id du produit
        
        // On récupère le panier existant
        $panier = $session->get('panier', []);
        
        // On ajoute le produit dans le panier s'il n'y est pas encore
        
        // Sinon on incrémente sa quantité
        
        if(empty($panier[$id])){
            $panier[$id] = 1;
        }else{
           $panier[$id]++;
        }

        $session->set('panier', $panier);
        
        //On redirige vers la page du panier
        return $this->redirectToRoute('app_cart');
    }



    #[Route('/cart/remove/{id}', name: 'remove')]
    public function remove(SessionInterface $session, int $id)
    {
        //On récupère l'id du produit
        

        // On récupère le panier existant
        $panier = $session->get('panier', []);

        // On retire le produit du panier s'il n'y a qu'1 exemplaire
        // Sinon on décrémente sa quantité
        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        $session->set('panier', $panier);
        
        //On redirige vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/delete/{id}', name: 'delete')]
    public function delete(SessionInterface $session, int $id)
    {
        //On récupère l'id du produit
        

        // On récupère le panier existant
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        $session->set('panier', $panier);
        
        //On redirige vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/empty', name: 'empty')]
    public function empty(SessionInterface $session)
    {
        $session->remove('panier');

        return $this->redirectToRoute('app_cart');
    }


   
  





















    
}