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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'task')]
    public function index( TaskRepository $repo, CategoryRepository $categoryRepo, UserRepository $userRepository): Response
    {
        $tasks = $repo->findAll();
        $
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

    #[Route('tasks/search', name: 'search')]
    public function search(Request $request, TaskRepository $taskRepo  ): Response {
     // on cherche plusierus tasks par un mot clef
     //on cree une instance
     $tasks= new Task();
 
     // on veut creer un formulaire
    $form = $this->createFormBuilder()
    ->add('query', TextType::class, [
     'label' => false,
     'attr' => [ 
         'class' => 'form-control',
         'placeholder' => 'Entrez un mot-clé'
     ]
 ])
 ->add('recherche', SubmitType::class, [
     'attr' => [
         'class' => 'btn btn-primary'
     ]
 ])
 ->getForm();


 $form->handleRequest($request);

 if ($form->isSubmitted() && $form->isValid()){

    $tasks=$taskRepo->findAllTasksBysearch($form->getData()['query']);

 }

    return $this->render('task/resultsOfSearch.html.twig', [
     'form' => $form->createView(),
     'tasks' => $tasks,  
 ]);

}
    



}








    


    


    
    


    
  
    

