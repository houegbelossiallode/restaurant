<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotNull;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(CategorieRepository $categorieRepository): Response
    {

        $categories = $categorieRepository->findByParent();
        
        return $this->render('categorie/index.html.twig', [
          'categories'=>$categories
        ]);
    }





    #[Route('/categorie/new', name: 'add_categorie')]
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {

        $categorie = new Categorie();
        
        $form = $this->createForm(CategorieType::class,$categorie);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {

            
            $manager = $doctrine->getManager();
            $manager->persist($categorie);
            $manager->flush();
           
            $this->addFlash("error", "a été ajouté avec succès" );
            return $this->redirectToRoute('app_categorie');
            
        }
        
        return $this->render('admin/categorie/new.html.twig', [
            'form' => $form->createView()
        ]);
    }




    
    #[Route('/categorie/edit/{id}', name: 'edit_categorie')]
    public function edit(ManagerRegistry $doctrine, Request $request, $id,Categorie $categorie = null ): Response
    {

        
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);
        $form = $this->createForm(CategorieType::class,$categorie);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {

            
            $manager = $doctrine->getManager();
            $manager->persist($categorie);
            $manager->flush();
           
            $this->addFlash("error", "a été ajouté avec succès" );
            return $this->redirectToRoute('app_categorie');
            
        }
        
        return $this->render('admin/categorie/edit.html.twig', [
            'form' => $form->createView(),
            'categorie'=> $categorie->getId()
        ]);
    }


    #[Route('/categorie/delete/{id}', name: 'categorie.delete')]
    public function  delete($id, ManagerRegistry $doctrine,Request $request) : Response 
   {
 
     
     $categorie = $doctrine->getRepository(Categorie::class)->find($id);
    
     if($categorie)
     {
         $manager = $doctrine->getManager();
         $manager->remove($categorie);
         $manager->flush();
         $this->addFlash('success',"Le plat a été supprimé avec succès");
         return $this->redirectToRoute('app_categorie');
        
 
         
     }else{
         $this->addFlash('error','Plat inexistant');
     }
 
       return $this->redirectToRoute('app_categorie');
 
 
     
     }









    
}