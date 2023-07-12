<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'task')]
    public function index( TaskRepository $repo, UserRepository $userRepository): Response
    {
        
        $users = $userRepository->findByTasks($tasks);
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'users' => $users

        ]);
    }
    #[Route('/task/newTask', name: 'create')]
    public function create(Task $task = null, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$task) {
            $task = new task();
        }
       
        $form= $this->createForm(TaskType::class,$task);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            if(!$task->getId()) {
                $task->setDeadline(new \Datetime());
                }  
            
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('showTask', ['id' => $task->getId()]);
          
        }


        return $this->render('task/create.html.twig', [
            'formTask' => $form->createView(),
           
        ]);
    }




    #[Route('/', name: 'home')]
    public function home()
        {

            return $this->render('task/home.html.twig');
  
        }
    

    #[Route("/task/{id}", name:"showTask")]
    public function show(Task $task)
    {    
        return $this->render('task/show.html.twig', [
            'task' => $task
        ]);
    }


}
