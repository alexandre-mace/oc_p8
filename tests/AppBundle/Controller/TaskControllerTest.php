<?php

// Tests/Controller/TaskControllerTest.php

namespace AppBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TaskControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/tasks');
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

    public function testDelete()
    {
       $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'a',
            'PHP_AUTH_PW'   => 'a'
        ));
        $client->request('GET', 'tasks/test-task-update/delete');
        $this->assertTrue($client->getResponse()->isRedirection());
        
        $crawler = $client->followRedirect();
        $this->assertContains(
            'La tâche a bien été supprimée.',
            $client->getResponse()->getContent()
        );
        $this->assertEquals(
            0,
            $crawler->filter('html:contains("test task update")')->count());
	}

}