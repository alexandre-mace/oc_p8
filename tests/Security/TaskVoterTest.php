<?php

namespace App\Tests\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Tests\Security\ExposedTaskVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class TaskVoterTest extends TestCase
{
    public function testAttributeSupports()
    {
        $task = new Task;
        $voter = new ExposedTaskVoter;
        $this->assertEquals(false, $voter->exposedSupports('abracadabra', $task));
    }
    public function testObjectSupports()
    {
        $user = new User;
        $voter = new ExposedTaskVoter;
        $this->assertEquals(false, $voter->exposedSupports('delete', $user));
    }
    public function testVoteOnAttribute()
    {
        $task = new Task;
        $voter = new ExposedTaskVoter;
        $token = $this->createMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->assertEquals(false, $voter->exposedVoteOnAttribute('delete', $task, $token));        
    }

    protected function setup()
    {
        $this->prophet = new \Prophecy\Prophet;
    }
}