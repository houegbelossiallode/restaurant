<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Form\PlatType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PlatController extends AbstractController
{
    #[Route('/plat', name: 'add_plat')]
    public function index(ManagerRegistry $doctrine): Response
    {
        
       
    $repository = $doctrine->getRepository(Plat::class);
    $plats = $repository->findAll();
   
    return $this->render('plat/liste.html.twig', [
    'plats' => $plats,

    ]);
  
            
        
        
    }



  
























        


  
        
    }