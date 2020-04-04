<?php
/**
 * Mission.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 15, 2020 at 17:49:34
 */

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Mission
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\MissionRepository")
 */
class Mission
{
    /**
     * The mission id.
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The mission title.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * The mission description.
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * The mission done.
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $done;

    /**
     * The mission user.
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="missions")
     */
    private $user;

    /**
     * The mission link.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * The mission date.
     * @var DateTimeInterface
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * Gets the mission id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the mission title.
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the mission title.
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the mission description.
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the message description.
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the mission done.
     * @return bool|null
     */
    public function getDone(): ?bool
    {
        return $this->done;
    }

    /**
     * Sets the mission done.
     * @param bool $done
     * @return $this
     */
    public function setDone(bool $done): self
    {
        $this->done = $done;

        return $this;
    }

    /**
     * Gets the mission user.
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Sets the mission user.
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Gets the mission link.
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * Sets the mission link.
     * @param string $link
     * @return $this
     */
    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Gets the mission date.
     * @return DateTimeInterface|null
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Sets the mission date.
     * @param DateTimeInterface|null $date
     * @return $this
     */
    public function setDate(?DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
