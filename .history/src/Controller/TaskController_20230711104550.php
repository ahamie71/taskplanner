<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\Category;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
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
        $tasks = $repo->findAll();
      
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            

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

                $task->setDeadline($form->get('deadline')->getData());
                $task->setUser($this->getUser());
                $task->setIsDone(False);
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

  
    #[Route('/task/search', name: 'search_task_by_category')]
    public function searchAlltaskByCategory(Request $request, CategoryRepository $categoryRepo, TaskRepository $taskRepo): Response
    {
        $categoryId = $request->query->get('category');
        $category = $categoryRepo->find($categoryId);
        $tasks = [];

        if ($category) {
            $tasks = $taskRepo->findByCategory($category);
        }

        return $this->render('task/index.html.twig', [
            'category' => $category,
            'tasks' => $tasks,
        ]);
    }
}
Dans cette version mise à jour, j'ai ajouté le CategoryRepository en tant qu'argument dans la méthode searchAlltaskByCategory. J'ai également modifié le code pour récupérer la catégorie correspondant à l'ID spécifié à partir du CategoryRepository.

La catégorie récupérée est ensuite utilisée pour obtenir les tâches associées en appelant la méthode findByCategory sur le TaskRepository.

Assurez-vous que vous avez correctement configuré les noms et les routes des catégories dans votre application Symfony.

Avec ces modifications, vous devriez être en mesure de récupérer les catégories par leur ID et de les utiliser pour rechercher les tâches correspondantes dans votre application.







    
}

    


    
    


    
  
    

