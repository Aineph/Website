<?php
/**
 * User.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 02, 2020 at 18:17:35
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * The user id.
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The user e-mail.
     * @var string
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * The user roles.
     * @var array
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * The user password.
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * The user is activated.
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $is_activated;

    /**
     * The user activation key.
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activation_key;

    /**
     * The user first name.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $first_name;

    /**
     * The user last name.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $last_name;

    /**
     * The user company.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $company;

    /**
     * The user address.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * The user city.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * The user zip.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $zip;

    /**
     * The user country.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * The user phone number.
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $phone_number;

    /**
     * The user messages.
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="user")
     */
    private $messages;

    /**
     * The user missions.
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Mission", mappedBy="user")
     */
    private $missions;

    /**
     * The user articles.
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user")
     */
    private $articles;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->missions = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    /**
     * Converts a user entity to string.
     * @return string
     */
    public function __toString()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /**
     * Gets the user id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the user e-mail.
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the user e-mail.
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     * @return string
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * Gets the user roles.
     * @return array
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Sets the user roles.
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Gets the user password.
     * @return string
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    /**
     * Sets the user password.
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Gets the salt.
     * @return string|void|null
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * Erases the credentials.
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Gets the user is activated.
     * @return bool|null
     */
    public function getIsActivated(): ?bool
    {
        return $this->is_activated;
    }

    /**
     * Sets the user is activated.
     * @param bool $is_activated
     * @return $this
     */
    public function setIsActivated(bool $is_activated): self
    {
        $this->is_activated = $is_activated;

        return $this;
    }

    /**
     * Gets the user activation key.
     * @return string|null
     */
    public function getActivationKey(): ?string
    {
        return $this->activation_key;
    }

    /**
     * Sets the user activation key.
     * @param string|null $activation_key
     * @return $this
     */
    public function setActivationKey(?string $activation_key): self
    {
        $this->activation_key = $activation_key;

        return $this;
    }

    /**
     * Gets the user first name.
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * Sets the user first name.
     * @param string $first_name
     * @return $this
     */
    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Gets the user last name.
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * Sets the user last name.
     * @param string $last_name
     * @return $this
     */
    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * Gets the user company.
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * Sets the user company.
     * @param string $company
     * @return $this
     */
    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Gets the user address.
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Sets the user address.
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Gets the user city.
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Sets the user city.
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Gets the user zip.
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * Sets the user zip.
     * @param string $zip
     * @return $this
     */
    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Gets the user country.
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Sets the user country.
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Gets the user phone number.
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    /**
     * Sets the user phone number.
     * @param string $phone_number
     * @return $this
     */
    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    /**
     * Gets the user messages.
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    /**
     * Adds a message to the user messages.
     * @param Message $message
     * @return $this
     */
    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    /**
     * Removes a message from the user messages.
     * @param Message $message
     * @return $this
     */
    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Gets the user missions.
     * @return Collection|Mission[]
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    /**
     * Adds a mission to the user missions.
     * @param Mission $mission
     * @return $this
     */
    public function addMission(Mission $mission): self
    {
        if (!$this->missions->contains($mission)) {
            $this->missions[] = $mission;
            $mission->setUser($this);
        }

        return $this;
    }

    /**
     * Removes a mission from the user missions.
     * @param Mission $mission
     * @return $this
     */
    public function removeMission(Mission $mission): self
    {
        if ($this->missions->contains($mission)) {
            $this->missions->removeElement($mission);
            // set the owning side to null (unless already changed)
            if ($mission->getUser() === $this) {
                $mission->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Gets the user articles.
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /**
     * Adds an article to the user articles.
     * @param Article $article
     * @return $this
     */
    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    /**
     * Removes an article from the user articles.
     * @param Article $article
     * @return $this
     */
    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }
}
