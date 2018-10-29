<?php

namespace App\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\Task;

class DeleteTaskHandler
{
    private $manager;
    private $flashBag;

    public function __construct(EntityManagerInterface $manager, FlashBagInterface $flashBag, AuthorizationCheckerInterface $authChecker)
    {
        $this->manager = $manager;
        $this->flashBag = $flashBag;
        $this->authChecker = $authChecker;
    }

    public function handle(Task $task)
    {
        if (!$this->authChecker->isGranted('delete', $task)) {
            throw new AccessDeniedException();
        }
        $this->manager->remove($task);
        $this->manager->flush();
        $this->flashBag->add('success', 'La tâche a bien été supprimée.');

        return true;
    }
}