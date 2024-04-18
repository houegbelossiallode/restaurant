<?php

namespace App\Controller;

use FedaPay\FedaPay;
use FedaPay\Transaction;
use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        // You can find your endpoint's secret key in your webhook settings
$endpoint_secret = 'wh_sandbox_gjPhqIrPz5L6JY7i3pmIrkCg';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_X_FEDAPAY_SIGNATURE'];
$event = null;

try {
    $event = \FedaPay\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch(\UnexpectedValueException $e) {
    // Invalid payload

    http_response_code(400);
    exit();
} catch(\FedaPay\Error\SignatureVerification $e) {
    // Invalid signature

    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->name) {
    case 'transaction.created':
        // Transaction created
        break;
    case 'transaction.approved':
        // Transaction approved
        break;
    case 'transaction.canceled':
        // Transaction canceled
        break;
    default:
        http_response_code(400);
        exit();
}

http_response_code(200);
        return $this->render('payment/index.html.twig', [
            
        ]);
    }




    #[Route('/encaisser', name: 'payer')]
    public function check(Request $request)
    {

     $nom = [
        "nom"=>  $request->get('firstname'),
        "prenom"=> $request->get('lastname'),
     ];
     dd($_POST);



    }


    




   // #[Route('/checkout', name: 'app_checkout')]
  //  public function checkout(): Response
  //  {



    //    FedaPay::setApiKey('pk_sandbox_G9xB3JPqYB-2BmXQPeY6aIOU');
    //    FedaPay::setEnvironment('Sandbox');
        // Créer la transaction
    //    $transaction = Transaction::create([
    //        "description" => "Article 2309ART",
    //        "amount" => 10000,
     //       "currency" => ["code" => "XOF"],
            
    //        "customer" => [
    //            "firstname" => "John",
    //            "lastname" => "Doe",
    //            "email" => "john.doe@gmail.com",
    //            "phone" => "+22966000000"
    //        ]
    //    ]);
/*
  Générer un lien sécurisé de paiement
 * {
 *   "token": "SECURED_TOKEN",
    "url": "https://process.fedapay.com/SECURED_TOKEN"
 * }
 */
   //$token = $transaction->generateToken();




//dd($token);

        
      
  //  }














    
}