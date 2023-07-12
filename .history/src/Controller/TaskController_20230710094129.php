<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'task')]
    public function index(TaskRepository $repo): Response
    {
        $articles = $repo->findAll();
        return $this->render('task/index.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/', name: 'home')]

    public function home()
        {
           
            return $this->render('task/home.html.twig');
  
        }
    


}
