<?php
/**
 * MessageManager.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 23, 2020 at 13:39:58
 */

namespace App\Service;

use App\Entity\Message;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Security;

/**
 * Class MessageService
 * @package App\Service
 */
class MessageService extends AbstractService implements ServiceInterface
{
    /**
     * The current message.
     * @var Message
     */
    private $message;

    /**
     * MessageManager constructor.
     * @param string $uploadDirectory
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(string $uploadDirectory, Security $security, EntityManagerInterface $entityManager)
    {
        parent::__construct($uploadDirectory, $security, $entityManager);
        $this->setObjectRepository($this->getEntityManager()->getRepository(Message::class));
        $this->setMessage(new Message());
    }

    /**
     * Creates the current message.
     */
    public function create(): void
    {
        if ($this->getUser()) {
            $this->getMessage()->setName($this->getUser()->getFirstName() . ' ' . $this->getUser()->getLastName());
            $this->getMessage()->setEmail($this->getUser()->getEmail());
            $this->getMessage()->setUser($this->getUser());
        }
        try {
            $this->getMessage()->setDate(new DateTime('now'));
        } catch (Exception $e) {
            $this->getMessage()->setDate(null);
        }
        $this->getEntityManager()->persist($this->getMessage());
        $this->getEntityManager()->flush();
    }

    /**
     * Deletes the current message.
     */
    public function delete(): void
    {
        if ($this->getMessage()) {
            $this->getEntityManager()->remove($this->getMessage());
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Gets the current message.
     * @return Message|null
     */
    public function getMessage(): ?Message
    {
        return $this->message;
    }

    /**
     * Sets the current message.
     * @param Message|null $message
     */
    public function setMessage(?Message $message): void
    {
        $this->message = $message;
    }
}
