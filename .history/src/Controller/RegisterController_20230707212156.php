<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
       $this->entityManager = $entityManager;
    }
    #[Route('SignIn', name: 'register')]
    public function index(Request $request,UserPasswordHasherInterface $encoder): Response
    {
        $notification= 'null';
        $user= new User();
        $form= $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){

            $user= $form->getData();
            $user_find= $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if(!$user_find){

                $password= $encoder->hashPassword($user,$user->getPassword());
                $user->setPassword($password);
            }
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $notification = 'your registration went well';
} else{
            $notification= 'your email already exists';
        }
    
        return $this->render('register/index.html.twig', [
             'form'=>$form->createView(),
             'notification'=>$notification,

        ]);
    }
}
