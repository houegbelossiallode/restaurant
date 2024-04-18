<?php

namespace App\Controller\Admin;

use App\Form\SearchType;
use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

    /**
     * @IsGranted("ROLE_ADMIN")
     */
class HomeController extends AbstractController
{
    #[Route('/admin', name: 'admin_home')]
    
    public function index(PlatRepository $platRepository,Request $request): Response
    {

       $donnees = [];
       $form = $this->createForm(SearchType::class);
       $form->handleRequest($request);
       
       if($form->isSubmitted() && $form->isValid())
       {
        
        $title = $form->getData();
      
        $donnees = $platRepository->search($title);
        
        
       }
 
       
        return $this->render('admin/index.html.twig',[
            
            'form'=> $form->createView(),
            'donnees'=> $donnees,
            
        ]);


    }

        
    
        

    
}