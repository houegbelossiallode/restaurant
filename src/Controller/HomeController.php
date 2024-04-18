<?php

namespace App\Controller;

use App\Entity\Plat;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Plat::class);
        $plats = $repository->findAll();
        return $this->render('home/index.html.twig', [
            'plats' => $plats,
        ]);


    }

        
        #[Route('/menu', name: 'app_menu')]
        public function menu(): Response
        {
            return $this->render('home/menu.html.twig');
        }


        #[Route('/about', name: 'app_about')]
        public function about(): Response
        {
            return $this->render('home/about.html.twig');
        }
        
       
        




     














        

    
}