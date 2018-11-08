<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Task
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Vous devez saisir un titre.")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez saisir du contenu.")
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDone;

<<<<<<< HEAD:src/AppBundle/Entity/Task.php
=======
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

>>>>>>> dev:src/Entity/Task.php
    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->isDone = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getIsDone()
    {
        return $this->isDone;
    }

    public function setIsDone($isDone)
    {
        $this->isDone = $isDone;
    }

    public function toggle($flag)
    {
        $this->isDone = $flag;
    }
<<<<<<< HEAD:src/AppBundle/Entity/Task.php
=======
    
    public function getAuthor(): ?User
    {
        return $this->author;
    }
    public function setAuthor(?User $author): self

    {
        $this->author = $author;

        return $this;
    }
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
>>>>>>> dev:src/Entity/Task.php
}
