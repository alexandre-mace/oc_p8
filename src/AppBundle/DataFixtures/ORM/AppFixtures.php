<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) 
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $userA = new User;
        $userA->setRole('ROLE_ADMIN');
        $userA->setUsername('a');
        $userA->setPassword($this->passwordEncoder->encodePassword(
             $userA,
             'a'
         ));
        $userA->setEmail('userA@test.com');

        $userB = new User;
        $userB->setRole('ROLE_USER');
        $userB->setUsername('b');
        $userB->setPassword($this->passwordEncoder->encodePassword(
             $userB,
             'b'
         ));
        $userB->setEmail('userB@test.com');

        $userC = new User;
        $userC->setRole('ROLE_USER');
        $userC->setUsername('anon');
        $userC->setPassword($this->passwordEncoder->encodePassword(
             $userC,
             'anon'
         ));
        $userC->setEmail('userC@test.com');

        $manager->persist($userA);
        $manager->persist($userB);
        $manager->persist($userC);
        $manager->flush();

        $tasks = Yaml::parseFile('src/AppBundle/DataFixtures/ORM/Task.yaml');


        foreach ($tasks as $taskdata) {
            $task = new Task();

            $task->setAuthor($userC);
            $task->setTitle($taskdata['title']);
            $task->setContent($taskdata['content']);

            $manager->persist($task);
            $manager->flush();

        }

        $manager->flush();
    }
}