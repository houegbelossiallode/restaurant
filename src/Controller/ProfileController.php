<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(UserRepository $userRepository): Response
    {
        $user = new User();
        $id = $this->getUser();
       
        $profiles = $userRepository->findBy(['id'=>$id]);
        return $this->render('profile/index.html.twig', [
            'profiles' => $profiles,
        ]);
    }




    #[Route('/profile/edit/{id}', name: 'profile.edit')]
    public function edit(Request $request,ManagerRegistry $doctrine, int $id,User $user = null)
    {
        
       
       $user = $doctrine->getRepository(User::class)->find($id);
       $form = $this->createForm(RegistrationFormType::class, $user);
       $form->remove('plainPassword');
       $form->handleRequest($request);
       
       if($form->isSubmitted() && $form->isValid()){
          
          
           $manager = $doctrine->getManager();
           
           $manager->persist($user);
           
           
           $manager->flush();
           $this->addFlash('success',"Vous avez modifier votre profile");
           $edit = $user->getId();
           return $this->redirectToRoute('app_profile');
       }
       return $this->render('profile/edit.html.twig',array(
           'form'=>$form->createView(),
           'user' => $user->getId()
       ));
   
       
        
        
    }
   












    
}