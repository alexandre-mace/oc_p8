<?php

// src/AppBundle/EventListener/UserSubscriber.php
namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use AppBundle\Entity\Product;
use Symfony\Component\Security\Core\Security;
use AppBundle\Entity\Task;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class TaskSubscriber implements EventSubscriber
{
    private $security;

    public function __construct(Security $security) 
    {
        $this->security = $security;
    }

    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->hydrateAuthor($args);
    }

    public function hydrateAuthor(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // upload only works for Trick entities
        if (!$entity instanceof Task) {
            return;
        }

        if ($this->security->getUser()) {
            $entity->setAuthor($this->security->getUser()); 
            dump($entity);           
        }
    }
}