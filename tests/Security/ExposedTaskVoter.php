<?php

namespace App\Tests\Security;

use App\Entity\Task;
use App\Entity\User;
use App\Security\TaskVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ExposedTaskVoter extends TaskVoter
{
    public function exposedSupports($attribute, $subject)
    {
    	return $this->supports($attribute, $subject);
    }
    public function exposedVoteOnAttribute($attribute, $object, TokenInterface $token)
    {
    	return $this->voteOnAttribute($attribute, $object, $token);
    }
}