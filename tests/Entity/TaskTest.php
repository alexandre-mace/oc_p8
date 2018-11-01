<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Task;
use App\Entity\User;

class TaskTest extends TestCase
{
    public function testEntity()
    {
        $task = new Task();
        $task->setTitle('test title');
        $task->setContent('test content');
        $task->toggle(true);
        $task->setIsDone(true);
        $datetime = new \Datetime;
        $task->setCreatedAt($datetime);
        $this->assertEquals('test title', $task->getTitle());
        $this->assertEquals('test content', $task->getContent());
        $this->assertEquals(true, $task->getIsDone());
        $this->assertEquals(true, $task->getIsDone());
        $this->assertEquals($datetime, $task->getCreatedAt());
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
