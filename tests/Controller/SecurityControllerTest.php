<?php

// Tests/Controller/SecurityControllerTest.php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
            $form['user[password][first]'] = 'test';
            $form['user[password][second]'] = 'test';
            $form['user[email]'] = 'test' . rand() . '@test.com';
            $form['user[role]']->select('ROLE_ADMIN');
            $client->submit($form);

            $this->assertTrue($client->getResponse()->isRedirection());

            $crawler = $client->followRedirect();
            $this->assertEquals(
                1,
                $crawler->filter('html:contains("L\'utilisateur a bien été ajouté.")')->count());
        }
        $client = self::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $crawler = $client
            ->submit($form,
                array(
                    '_username' => $username,
                    '_password' => 'test',
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
}