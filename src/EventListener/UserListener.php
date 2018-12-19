<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 19/12/18
 * Time: 19:55
 */

namespace App\EventListener;


use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UserListener
{
    private $cacheDriver;

    public function __construct($cacheDriver)
    {
        $this->cacheDriver = $cacheDriver;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof User) {
            return;
        }
        $this->cacheDriver->expire('[users_all][1]', 0);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof User) {
            return;
        }
        $this->cacheDriver->expire('[users_all][1]', 0);
    }
}