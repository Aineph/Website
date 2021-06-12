<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sender_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sender_email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $object;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getSenderName(): ?string
    {
        return $this->sender_name;
    }

    /**
     * @param string $sender_name
     * @return $this
     */
    public function setSenderName(string $sender_name): self
    {
        $this->sender_name = $sender_name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSenderEmail(): ?string
    {
        return $this->sender_email;
    }

    /**
     * @param string $sender_email
     * @return $this
     */
    public function setSenderEmail(string $sender_email): self
    {
        $this->sender_email = $sender_email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getObject(): ?string
    {
        return $this->object;
    }

    /**
     * @param string $object
     * @return $this
     */
    public function setObject(string $object): self
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
