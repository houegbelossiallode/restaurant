<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_users')]
    public function index(UserRepository $userRepository): Response
    {
        $nombres = $userRepository->findCount();
        
        $user = $userRepository->findBy([], ['nom' => 'asc']);
        
        return $this->render('admin/user/index.html.twig',[
            'user'=> $user,
            'nombres'=> $nombres
        ]);
    }
}