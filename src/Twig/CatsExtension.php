<?php

namespace App\Twig;

use Twig\TwigFunction;
use App\Entity\Notifications;
use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CatsExtension extends AbstractExtension
{
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        
    }


    public function getFunctions() : array
    {
        return[
          new TwigFunction('nbArticle',[$this,'getArticle'])  
        ];
    }


public function getArticle()
{
    
return $count = COUNT($data);   
  
}
 
}










?>