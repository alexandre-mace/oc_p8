<?php

// Tests/Controller/SecurityControllerTest.php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'a',
            'PHP_AUTH_PW' => 'a'
        ));
        $crawler = $client->request('GET', '/users/create');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $username = 'testLogin' . rand();
        if ($client->getResponse()->isSuccessful()) {
            $form = $crawler->selectButton('Ajouter')->form();
            $form['user[username]'] = $username;
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
        }
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $crawler = $client
            ->submit($form,
                array(
                    'username' => $username,
                    'password' => 'test',
                )
        );

        $this->assertTrue($client->getResponse()->isRedirection());       
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testLogout()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'a',
            'PHP_AUTH_PW' => 'a'
        ));
        $crawler = $client->request('GET', '/users/create');
        $this->assertTrue($client->getResponse()->isSuccessful());


        if ($client->getResponse()->isSuccessful()) {
            $link = $crawler
                ->filter('a:contains("Se déconnecter")')
                ->first()
                ->link()
            ;

            $crawler = $client->click($link);

            $this->assertTrue($client->getResponse()->isRedirection());       
            $crawler = $client->followRedirect();
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
        }
    }

    public function testOnAuthenticationSuccessWithTargetPath(){
        $client = self::createClient();
        $client->request('GET', '/tasks/todo');
        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();

        $form = $crawler->selectButton('Se connecter')->form();
        $crawler = $client
            ->submit($form,
                array(
                    'username' => 'a',
                    'password' => 'a',
                )
            );

        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}