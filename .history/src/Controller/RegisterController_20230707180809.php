<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{

    private $entityManager;
    
    #[Route('/register', name: 'register')]
    public function index(): Response
    {
        return $this->render('register/index.html.twig', [
           
        ]);
    }
}
