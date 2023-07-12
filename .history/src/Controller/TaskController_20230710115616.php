<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'task')]
    public function index(TaskRepository $repo): Response
    {
        $tasks = $repo->findAll();
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks
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
            
            
            $entityManager->persist($task);
            $entityManager->flush();

          
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
    

    #[Route("/blog/{id}", name: "blog_show")]
    public function show(Article $article)
    {
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }


}
