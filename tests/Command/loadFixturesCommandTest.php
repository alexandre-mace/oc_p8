<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\DataFixtures\AppFixtures;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\Persistence\ObjectManager;


class loadFixturesCommandTest extends WebTestCase
{
    public function testLoad() {
        $client = self::createClient();
        $container = $client->getContainer();
        $em = $container->get('doctrine.dbal.default_connection');
        foreach($em->getSchemaManager()->listTableNames() as $tableName)
        {
          $em->exec('DELETE FROM ' . $tableName);
        }
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();

        $fixture = new AppFixtures($container->get('security.password_encoder'));
            $fixture->load($entityManager);
        $crawler = $client->request('GET', '/tasks');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains(
            'task test 1',
            $client->getResponse()->getContent()
        );
    }
}