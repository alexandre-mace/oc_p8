<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Handler\CreateTaskHandler;
use App\Handler\EditTaskHandler;
use App\Handler\DeleteTaskHandler;
use App\Handler\ToggleTaskHandler;

/**
 * Class TaskController
 */
class TaskController extends Controller
{
    /**
     * @Route("/tasks/todo", name="task_to_do_list")
     */
    public function toDoList(TaskRepository $repository)
    {
        return $this->render('task/list.html.twig', ['tasks' => $repository->findToDoTasks()]);
    }

    /**
     * @Route("/tasks/done", name="task_done_list")
     */
    public function doneList(TaskRepository $repository)
    {
        return $this->render('task/list.html.twig', ['tasks' => $repository->findDoneTasks()]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function create(Request $request, CreateTaskHandler $handler)
    {
        $form = $this->createForm(TaskType::class)->handleRequest($request);
        if ($handler->handle($form)) {
            return $this->redirectToRoute('task_to_do_list');
        }
        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{slug}/edit", name="task_edit")
     */
    public function edit(Task $task, Request $request, EditTaskHandler $handler)
    {
        $form = $this->createForm(TaskType::class, $task)->handleRequest($request);
        if ($handler->handle($form)) {
            return $this->redirect($request->request->get('referer'));
        }
        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{slug}/delete", name="task_delete")
     */
    public function delete(Task $task, DeleteTaskHandler $handler, Request $request)
    {
        $handler->handle($task);
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/tasks/{slug}/toggle", name="task_toggle")
     */
    public function toggle(Task $task, ToggleTaskHandler $handler, Request $request)
    {
        $handler->handle($task);
        return $this->redirect($request->headers->get('referer'));
    }
}
