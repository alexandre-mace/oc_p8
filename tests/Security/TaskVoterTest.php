<?php

namespace App\Tests\Security;

use Symfony\Component\Form\Exception\LogicException;
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
        $authChecker = $this->createMock('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface');
        $voter = new ExposedTaskVoter($authChecker);
        $this->assertEquals(false, $voter->exposedSupports('abracadabra', $task));
    }
    public function testObjectSupports()
    {
        $user = new User;
        $authChecker = $this->createMock('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface');
        $voter = new ExposedTaskVoter($authChecker);
        $this->assertEquals(false, $voter->exposedSupports('delete', $user));
    }
    public function testVoteOnAttribute()
    {
        $task = new Task;
        $authChecker = $this->createMock('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface');
        $voter = new ExposedTaskVoter($authChecker);
        $token = $this->createMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->assertEquals(false, $voter->exposedVoteOnAttribute('delete', $task, $token));        
    }

    public function testVoteOnAttributeLogicExeption()
    {
        $task = new Task;
        $authChecker = $this->createMock('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface');
        $voter = new ExposedTaskVoter($authChecker);
        $token = $this->createMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $user = new User();
        $token->expects($this->any())
            ->method('getUser')
            ->willReturn($user);
        try {
            $voter->exposedVoteOnAttribute('abracadabra', $task, $token);
        } catch (LogicException $exception) {
            $this->assertTrue($exception instanceof LogicException);
        }
    }
}