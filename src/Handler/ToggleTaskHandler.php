<?php

namespace App\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use App\Entity\Task;

class ToggleTaskHandler
{
    private $manager;
    private $flashBag;

    public function __construct(EntityManagerInterface $manager, FlashBagInterface $flashBag)
    {
        $this->manager = $manager;
        $this->flashBag = $flashBag;
    }

    public function handle(Task $task)
    {
        $task->toggle(!$task->getIsDone());
        $this->manager->flush();
        $this->flashBag->add('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return true;
    }
}