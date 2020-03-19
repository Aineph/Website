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

class MessageService extends AbstractService implements ServiceInterface
{
    /**
     * @var Message
     */
    private $message;

    /**
     * MessageManager constructor.
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        parent::__construct($security, $entityManager);
        $this->setObjectRepository($this->getEntityManager()->getRepository(Message::class));
        $this->setMessage(new Message());
    }

    /**
     *
     */
    public function create()
    {
        if ($this->getUser()) {
            $this->getMessage()->setName($this->getUser()->getFirstName() . ' ' . $this->getUser()->getLastName());
            $this->getMessage()->setEmail($this->getUser()->getEmail());
            $this->getMessage()->setUser($this->getUser()->getId());
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
     * @param int $message
     */
    public function delete(int $message)
    {
        $messageRepository = $this->getEntityManager()->getRepository(Message::class);
        $messageEntity = $messageRepository->find($message);

        if ($messageEntity) {
            $this->getEntityManager()->remove($messageRepository->find($messageEntity));
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @param Message $message
     */
    public function setMessage(Message $message): void
    {
        $this->message = $message;
    }
}
