<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\Category;
use App\Repository\TaskRepository;

use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'task')]
    public function index(Request $request, TaskRepository $taskRepo, CategoryRepository $categoryRepo): Response
    {
        $categories = $categoryRepo->findAll();
        $selectedCategoryId = $request->query->get('category');
        $tasks = [];

        if ($selectedCategoryId) {
            $category = $categoryRepo->find($selectedCategoryId);
            if ($category) {
                $tasks = $category->getTasks();
            }
        } else {
            $tasks = $taskRepo->findAll();
        }

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'categories' => $categories,
            'selectedCategoryId' => $selectedCategoryId,
        ]);


    }

    #[Route('/task/newTask', name: 'create')]
    #[Route("/task/{id}/edit", name: "edit")]
    public function create(Task $task = null, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$task) {
            $task = new task();
        }
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$task->getId()) {

                $task->setDeadline($form->get('deadline')->getData());
                $task->setUser($this->getUser());
                $task->setIsDone(False);
            } else {
                if ($task->getUser() !== $this->getUser()) {
                    throw $this->createAccessDeniedException('Access denied.'); // Rediriger ou afficher une erreur si l'accès est refusé
                }

            }
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('showTask', ['id' => $task->getId()]);

        }
        return $this->render('task/create.html.twig', [
            'formTask' => $form->createView(),
            'editMode' => $task->getId() !== null
        ]);
    }
    #[Route('/', name: 'home')]
    public function home()
    {
        return $this->render('task/home.html.twig');
    }
    #[Route("/task/{id}", name: "showTask")]

    public function show(Task $task)
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,

        ]);
    }
    #[Route('tasks/search', name: 'search')]
    public function search(Request $request, TaskRepository $taskRepo): Response
    {
        // on cherche plusierus tasks par un mot clef
        //on cree une instance
        $tasks = new Task();

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

        if ($form->isSubmitted() && $form->isValid()) {
            $tasks = $taskRepo->findAllTasksBysearch($form->getData()['query']);
        }
        return $this->render('task/resultsOfSearch.html.twig', [
            'form' => $form->createView(),
            'tasks' => $tasks,
        ]);

    }

    #[Route("/task/{id}/complete", name: "task_complete")]
    public function complete($id, Task $task, EntityManagerInterface $entityManager, Request $request)
    {

        $task = $entityManager->getRepository(Task::class)->find($id);
        if (!$task) {
            throw $this->createNotFoundException('Task not found.');
        }
        $submittedToken = $request->query->get('_csrf_token');
        if (!$this->isCsrfTokenValid('task_complete' . $task->getId(), $submittedToken)) {
            throw new AccessDeniedException('Invalid CSRF token.');
        }


        if ($task->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Access denied.'); // Rediriger ou afficher une erreur si l'accès est refusé
        } else {
            $task->setIsDone(true);
            $entityManager->flush();
            return new RedirectResponse($this->generateUrl('task'));
        }
    }

    #[Route("/task/incomplete/{id}", name: "task_incomplete")]

    public function incompleteTask($id, EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {
        $task = $entityManager->getRepository(Task::class)->find($id);
        if (!$task) {
            throw $this->createNotFoundException('Task not found.');
        }
        $submittedToken = $request->query->get('_csrf_token');
        if (!$this->isCsrfTokenValid('task_complete' . $task->getId(), $submittedToken)) {
            throw new AccessDeniedException('Invalid CSRF token.');
        }
        if ($task->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Access denied.');
        }
        $task->setIsDone(false);
        $entityManager->flush();
        return $this->redirectToRoute('task');
    }
}