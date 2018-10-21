<?php

// src/AppBundle/EventListener/UserSubscriber.php
namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use AppBundle\Entity\Product;
use Symfony\Component\Security\Core\Security;
use AppBundle\Entity\Task;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use AppBundle\Service\Slugger;

class TaskSubscriber implements EventSubscriber
{
    private $security;
    private $slugger;

    public function __construct(Security $security, Slugger $slugger) 
    {
        $this->security = $security;
        $this->slugger = $slugger;
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
        $this->hydrateAuthor($args);
        $this->slugify($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->slugify($args);
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
        }
    }
    public function slugify(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // upload only works for Task entities
        if (!$entity instanceof Task) {
            return;
        }

        $title = $entity->getTitle();

        $slug = $this->slugger->slugify($title);
        $entity->setSlug($slug);    
    }
}