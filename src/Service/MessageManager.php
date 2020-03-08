<?php
/**
 * MessageManager.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 23, 2020 at 13:39:58
 */

namespace App\Service;

use App\Entity\Message;
use App\Pagination\Paginator;
use App\Repository\MessageRepository;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Exception;

class MessageManager
{
    /**
     * @var Message
     */
    private $message;

    /**
     * @var MessageRepository
     */
    private $messageRepository;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @param int $page
     * @return Paginator
     */
    public function getMessagePage(int $page)
    {
        return $this->getMessageRepository()->findLatest($page);
    }

    /**
     * @param int $message
     * @return Message|null
     */
    public function get(int $message): ?Message
    {
        return $this->getMessageRepository()->find($message);
    }

    /**
     * @param object|null $user
     */
    public function save(?object $user)
    {
        if ($user != null) {
            $this->getMessage()->setName($user->getFirstName() . ' ' . $user->getLastName());
            $this->getMessage()->setEmail($user->getEmail());
            $this->getMessage()->setUser($user->getId());
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
        $this->getEntityManager()->remove($this->getMessageRepository()->find($message));
        $this->getEntityManager()->flush();
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

    /**
     * @return MessageRepository
     */
    public function getMessageRepository(): MessageRepository
    {
        return $this->messageRepository;
    }

    /**
     * @param object $messageRepository
     */
    public function setMessageRepository(object $messageRepository): void
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * @return ObjectManager
     */
    public function getEntityManager(): ObjectManager
    {
        return $this->entityManager;
    }

    /**
     * @param ObjectManager $entityManager
     */
    public function setEntityManager(ObjectManager $entityManager): void
    {
        $this->entityManager = $entityManager;
    }
}
