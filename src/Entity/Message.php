<?php
/**
 * Message.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 23, 2020 at 13:09:44
 */

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Message
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * The message id.
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The message name.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * The message e-mail.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * The message object.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $object;

    /**
     * The message content.
     * @var string
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * The message user.
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messages")
     */
    private $user;

    /**
     * The message date.
     * @var DateTimeInterface
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * Gets the message id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the message name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the message name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the message e-mail.
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the message e-mail.
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Gets the message Object.
     * @return string|null
     */
    public function getObject(): ?string
    {
        return $this->object;
    }

    /**
     * Sets the message Object.
     * @param string $object
     * @return $this
     */
    public function setObject(string $object): self
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Gets the message content.
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Sets the message content.
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Gets the message user.
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Sets the message user.
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Gets the message date.
     * @return DateTimeInterface|null
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Sets the message date.
     * @param DateTimeInterface $date
     * @return $this
     */
    public function setDate(?DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
