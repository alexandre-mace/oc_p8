<?php

// src/App/EventListener/UserSubscriber.php
namespace App\EventListener;

use Doctrine\Common\EventSubscriber;
use Symfony\Component\UserPasswordEncoderInterface\Core\UserPasswordEncoderInterface;
use App\Entity\User;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class UserSubscriber implements EventSubscriber
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) 
    {
        $this->encoder = $encoder;
    }

    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate'
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->encode($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->encode($args);
    }

    public function encode(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // upload only works for Trick entities
        if (!$entity instanceof User) {
            return;
        }

        if ($entity->getPassword()) {
            $password = $this->encoder->encodePassword($entity, $entity->getPassword());
            $entity->setPassword($password); 
        }
    }
}