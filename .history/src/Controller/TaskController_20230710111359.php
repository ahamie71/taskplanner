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
        $tasks = $repo->findAll();
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks
        ]);
    }
    #[Route('/task/newTask', name: 'create')]
    public function create(Article $article = null, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$article) {
            $article = new Article();
        }
       
        $form= $this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()) {
            $article->setCreatedAt(new \Datetime());
            }
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }


        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode'   => $article->getId() !== null
        ]);
    }


    #[Route('/', name: 'home')]

    public function home()
        {
           
            return $this->render('task/home.html.twig');
  
        }
    


}
