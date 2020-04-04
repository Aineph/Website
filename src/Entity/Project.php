<?php
/**
 * Project.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 18, 2020 at 21:51:38
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Project
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
{
    /**
     * The project id.
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The project name.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * The project description.
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * The project type.
     * @var int
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * The project link.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * The project image.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * The project file.
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * Gets the project id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the project name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the project name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the project description.
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the project description.
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the project type.
     * @return int|null
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * Sets the project type.
     * @param int $type
     * @return $this
     */
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets the project link.
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * Sets the project link.
     * @param string $link
     * @return $this
     */
    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Gets the project image.
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Sets the project image.
     * @param string|null $image
     * @return $this
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Gets the project file.
     * @return string|null
     */
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * Sets the project file.
     * @param string|null $file
     * @return $this
     */
    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }
}
