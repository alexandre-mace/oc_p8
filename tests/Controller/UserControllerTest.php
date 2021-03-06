<?php

// Tests/Controller/UserControllerTest.php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserControllerTest extends WebTestCase
{

    public function testList()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'a',
            'PHP_AUTH_PW' => 'a'
        ));
        $client->request('GET', '/users');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testCreate()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'a',
            'PHP_AUTH_PW' => 'a'
        ));
        $crawler = $client->request('GET', '/users/create');
        $this->assertTrue($client->getResponse()->isSuccessful());

        if ($client->getResponse()->isSuccessful()) {
            $form = $crawler->selectButton('Ajouter')->form();
            $form['user[username]'] = 'testadd' .rand();
            $form['user[plainPassword][first]'] = 'test';
            $form['user[plainPassword][second]'] = 'test';
            $form['user[email]'] = 'test' . rand() . '@test.com';
            $form['user[roles]']->select('ROLE_ADMIN');
            $client->submit($form);

            $this->assertTrue($client->getResponse()->isRedirection());

            $crawler = $client->followRedirect();
            $this->assertEquals(
                1,
                $crawler->filter('html:contains("L\'utilisateur a bien été ajouté.")')->count());
            $this->assertContains(
                'testadd',
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
        $crawler = $client->request('GET', '/users/create');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $clientName = rand();
        if ($client->getResponse()->isSuccessful()) {
            $form = $crawler->selectButton('Ajouter')->form();
            $form['user[username]'] = $clientName;
            $form['user[email]'] = 'test' . rand() . '@test.com';
            $form['user[plainPassword][first]'] = 'test';
            $form['user[plainPassword][second]'] = 'test';
            $form['user[roles]']->select('ROLE_ADMIN');
            $client->submit($form);

            $this->assertTrue($client->getResponse()->isRedirection());

            $crawler = $client->followRedirect();
            $this->assertEquals(
                1,
                $crawler->filter('html:contains("L\'utilisateur a bien été ajouté.")')->count());
        }
        $crawler = $client->request('GET', '/users');
        $this->assertTrue($client->getResponse()->isSuccessful());
        
        $link = $crawler
            ->filter('a:contains("Edit")')
            ->last()
            ->link();
        $crawler = $client->click($link);

        $clientEditedName = rand();
        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = $clientEditedName;
        $form['user[plainPassword][first]'] = 'test';
        $form['user[plainPassword][second]'] = 'test';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();
        
        $this->assertEquals(
            1,
            $crawler->filter('html:contains("L\'utilisateur a bien été modifié")')->count());
        $this->assertContains(
            (string)$clientEditedName,
            $client->getResponse()->getContent()
        );
    }

    public function testInstanciateRepository() 
    {
        $metadata = $this->createMock('Doctrine\ORM\Mapping\ClassMetadata');
        $manager = $this->createMock('Doctrine\ORM\EntityManagerInterface');
        $manager->expects($this->any())
            ->method('getClassMetadata')
            ->willReturn($metadata);
        $registry = $this->createMock('Symfony\Bridge\Doctrine\RegistryInterface');
        $registry->expects($this->any())
            ->method('getManagerForClass')
            ->willReturn($manager);
        $repository = new UserRepository($registry);
        $this->assertTrue($repository instanceof UserRepository);
    }
}