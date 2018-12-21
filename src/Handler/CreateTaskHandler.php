<?php

namespace App\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CreateTaskHandler
{
    private $manager;
    private $flashBag;

    public function __construct(EntityManagerInterface $manager, FlashBagInterface $flashBag)
    {
        $this->manager = $manager;
        $this->flashBag = $flashBag;
    }

    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($form->getData());
            $this->manager->flush();
            $this->flashBag->add('success', 'La tâche a été bien été ajoutée.');
            return true;
        }

        return false;
    }
}