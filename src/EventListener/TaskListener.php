<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 19/12/18
 * Time: 18:11
 */

namespace App\EventListener;


use App\Entity\Task;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TaskListener
{
    private $cacheDriver;

    public function __construct($cacheDriver)
    {
        $this->cacheDriver = $cacheDriver;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Task) {
            return;
        }
        $this->cacheDriver->expire('[tasks_all][1]', 0);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Task) {
            return;
        }
        $this->cacheDriver->expire('[tasks_all][1]', 0);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Task) {
            return;
        }
        $this->cacheDriver->expire('[tasks_all][1]', 0);
    }
}