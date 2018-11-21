<?php

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TaskVoter extends Voter
{
    const DELETE = 'delete';
    private $authChecker;

    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array( self::DELETE))) {
            return false;
        }

        // only vote on Task objects inside this voter
        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        $task = $subject;

        switch ($attribute) {
            case self::DELETE:
                return $this->hasRight($task, $user);
        }
        throw new LogicException('This code shouldn\'t be reached');
    }
    
    private function hasRight(Task $task, User $user)
    {
        if ($task->getAuthor()->getUsername() === "anon") {
            return $this->authChecker->isGranted('ROLE_ADMIN');
        }
        return $user === $task->getAuthor();
    }
}