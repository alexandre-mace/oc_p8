<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Handler\CreateTaskHandler;
use App\Handler\EditTaskHandler;
use App\Handler\DeleteTaskHandler;
use App\Handler\ToggleTaskHandler;

class TaskController extends Controller
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function list()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('App:Task')->findAll()]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     * @Security("is_granted('ROLE_USER')")
     */
    public function create(Request $request, CreateTaskHandler $handler)
    {
        $form = $this->createForm(TaskType::class)->handleRequest($request);
        if ($handler->handle($form)) {
            return $this->redirectToRoute('task_list');
        }
        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{slug}/edit", name="task_edit")
     * @Security("is_granted('ROLE_USER')")
     */
    public function edit(Task $task, Request $request, EditTaskHandler $handler)
    {
        $form = $this->createForm(TaskType::class, $task)->handleRequest($request);
        if ($handler->handle($form)) {
            return $this->redirectToRoute('task_list');
        }
        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{slug}/toggle", name="task_toggle")
     * @Security("is_granted('ROLE_USER')")     
     */
    public function toggle(Task $task, ToggleTaskHandler $handler)
    {
        $handler->handle($task);
        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{slug}/delete", name="task_delete")
     * @Security("is_granted('ROLE_USER')")
     */
    public function deleteTask(Task $task, DeleteTaskHandler $handler)
    {
        $handler->handle($task);
        return $this->redirectToRoute('task_list');
    }
}
