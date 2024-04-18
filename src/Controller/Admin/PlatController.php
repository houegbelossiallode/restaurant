<?php

namespace App\Controller\Admin;

use App\Entity\Plat;
use App\Form\PlatType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin', name: 'admin_')]
class PlatController extends AbstractController
{



    #[Route('/listeplat', name: 'plat.liste')]
    public function liste(ManagerRegistry $doctrine): Response
 {
    $repository = $doctrine->getRepository(Plat::class);
    $plats = $repository->findAll();
   
    return $this->render('admin/plat/liste.html.twig', [
    'plats' => $plats,

    ]);
  
 }

    
    #[Route('/plat', name: 'add_plat')]
    public function add(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        
        $plat = new Plat();
        
        $form = $this->createForm(PlatType::class,$plat);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {


          
            $image = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('plat_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $plat->setImage($newFilename);
            }

            


            
            $manager = $doctrine->getManager();
            $manager->persist($plat);
            $manager->flush();
            $this->addFlash("error", "a été ajouté avec succès" );
            return $this->redirectToRoute('admin_plat.liste');
            
        }
        
        else{
            
            return $this->render('admin/plat/index.html.twig', [
                'form' => $form->createView(),
            ]);
            
        }
        
    }



 #[Route('/edit/{id}', name: 'plat.edit')]
 public function edit(Request $request,ManagerRegistry $doctrine,$id,SluggerInterface $slugger,Plat $plat = null)
 {
     
    
    $plat = $doctrine->getRepository(Plat::class)->find($id);
    $form = $this->createForm(PlatType::class, $plat);
    $form->handleRequest($request);
  
    if($form->isSubmitted() && $form->isValid()){
       
       


 $image = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('plat_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $plat->setImage($newFilename);
            }
       
        $manager = $doctrine->getManager();
        $manager->persist($plat);
        $manager->flush();
        $this->addFlash('success',$plat->getTitle()."a été édité avec succès");
        $edit = $plat->getId();
        return $this->redirectToRoute('admin_plat.liste');
    }
    return $this->render('admin/plat/edit.html.twig',array(
        'form'=>$form->createView(),
        'plat' => $plat->getId()
    ));

    
     
     
 }



 #[Route('/delete/{id}', name: 'plat.delete')]
   public function  delete(Plat $plat = null,$id, ManagerRegistry $doctrine,Request $request) : Response 
  {

    
    $plat = $doctrine->getRepository(Plat::class)->find($id);
   
    if($plat)
    {
        $manager = $doctrine->getManager();
        $manager->remove($plat);
        $manager->flush();
        $this->addFlash('success',$plat->getTitle()."a été édité avec succès");
        $edit = $plat->getId();
        return $this->redirectToRoute('plat.liste');
       

        
    }else{
        $this->addFlash('error','Plat inexistant');
    }

      return $this->redirectToRoute('admin_plat.liste');


    
    }



   

























        


  
        
    }