<?php

// Tests/Controller/TaskControllerTest.php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TaskControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'a',
            'PHP_AUTH_PW' => 'a'
        ));
        $client->request('GET', '/tasks/todo');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testCreate()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'a',
            'PHP_AUTH_PW' => 'a'
        ));
        $crawler = $client->request('GET', '/tasks/create');
        $this->assertTrue($client->getResponse()->isSuccessful());

        if ($client->getResponse()->isSuccessful()) {
            $form = $crawler->selectButton('Ajouter')->form();
            $form['task[title]'] = 'test task add';
            $form['task[content]'] = 'test task add test task add test task add test task add test task add';
            $client->submit($form);

            $this->assertTrue($client->getResponse()->isRedirection());

            $crawler = $client->followRedirect();
            $this->assertContains(
                'La tâche a été bien été ajoutée.',
                $client->getResponse()->getContent()
            );
            $this->assertContains(
                'test task add',
                $client->getResponse()->getContent()
            );
        }
    }
    
    public function testEdit()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'a',
            'PHP_AUTH_PW'   => 'a'
        ));
        $crawler = $client->request('GET', '/tasks/test-task-add/edit');
        $this->assertTrue($client->getResponse()->isSuccessful());

        if ($client->getResponse()->isSuccessful()) {
            $form = $crawler->selectButton('Modifier')->form();
            $form['task[title]'] = 'test task update';
            $form['task[content]'] = 'test task update test task update test task update';

            $client->submit($form);
            $this->assertTrue($client->getResponse()->isRedirection());
            $crawler = $client->followRedirect();
            
            $this->assertContains(
                'La tâche a bien été modifiée.',
                $client->getResponse()->getContent()
            );
            $this->assertContains(
                'test task update',
                $client->getResponse()->getContent()
            );
        }
    }

    public function testToggleTask() 
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'a',
            'PHP_AUTH_PW'   => 'a'
        ));
        $crawler = $client->request('GET', '/tasks/todo');
        $form = $crawler->selectButton('Marquer comme faite')->last()->form();
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();
        $this->assertContains(
            'La tâche test task update a bien été marquée comme faite.',
            $client->getResponse()->getContent()
        );
    }

    public function testDelete()
    {
       $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'a',
            'PHP_AUTH_PW'   => 'a'
        ));
        $crawler = $client->request('GET', '/tasks/todo');
        $form = $crawler->selectButton('Supprimer')->last()->form();
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();
        $this->assertContains('La tâche a bien été supprimée.', $client->getResponse()->getContent());
	}
    public function testAnonDelete()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'anon',
            'PHP_AUTH_PW' => 'anon'
        ));
        $crawler = $client->request('GET', '/tasks/create');
        $this->assertTrue($client->getResponse()->isSuccessful());

        if ($client->getResponse()->isSuccessful()) {
            $form = $crawler->selectButton('Ajouter')->form();
            $form['task[title]'] = 'test task voter';
            $form['task[content]'] = 'test task voter';
            $client->submit($form);

            $this->assertTrue($client->getResponse()->isRedirection());

            $crawler = $client->followRedirect();
            $this->assertContains(
                'La tâche a été bien été ajoutée.',
                $client->getResponse()->getContent()
            );
            $this->assertContains(
                'test task voter',
                $client->getResponse()->getContent()
            );
        }

        $client->request('GET', 'tasks/test-task-voter/delete');
        $this->assertTrue($client->getResponse()->isRedirection());
        
        $crawler = $client->followRedirect();
        $this->assertEquals(
            1,
            $crawler->filter('html:contains("La tâche a bien été supprimée.")')->count());       
    }
    public function testDeleteException()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'a',
            'PHP_AUTH_PW' => 'a'
        ));
        $crawler = $client->request('GET', '/tasks/create');
        $this->assertTrue($client->getResponse()->isSuccessful());

        if ($client->getResponse()->isSuccessful()) {
            $form = $crawler->selectButton('Ajouter')->form();
            $form['task[title]'] = 'test exception delete';
            $form['task[content]'] = 'test exception delete';
            $client->submit($form);

            $this->assertTrue($client->getResponse()->isRedirection());

            $crawler = $client->followRedirect();
            $this->assertContains(
                'La tâche a été bien été ajoutée.',
                $client->getResponse()->getContent()
            );
            $this->assertContains(
                'test exception delete',
                $client->getResponse()->getContent()
            );
        }

       $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'b',
            'PHP_AUTH_PW'   => 'b'
        ));
        $crawler = $client->request('GET', '/tasks/todo');
        $form = $crawler->selectButton('Supprimer')->last()->form();
        $client->submit($form);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}