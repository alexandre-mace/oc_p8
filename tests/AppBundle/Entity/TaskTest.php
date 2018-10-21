<?php

namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;

class TaskTest extends TestCase
{
    public function testEntity()
    {
        $task = new Task();
        $task->setTitle('test title');
        $task->setContent('test content');

        $this->assertEquals('test title', $task->getTitle());
        $this->assertEquals('test content', $task->getContent());
        $this->assertNull($task->getId());
    }

    public function testEntityRelations()
    {
        $user = new User;
        $task = new Task();        
        $task->setAuthor($user);
        $this->assertEquals($user, $task->getAuthor());
    }
}
