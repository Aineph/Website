<?php
/**
 * AbstractService.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 14, 2020 at 17:16:33
 */

namespace App\Service;

use App\Entity\User;
use App\Pagination\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\Security;

abstract class AbstractService implements ServiceInterface
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var ObjectRepository
     */
    private $objectRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * AbstractService constructor.
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->setUser($security->getUser());
        $this->setEntityManager($entityManager);
    }

    /**
     * @inheritDoc
     */
    public function get(int $id): object
    {
        return $this->getObjectRepository()->find($id);
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->getObjectRepository()->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getPage(int $page): Paginator
    {
        return $this->getObjectRepository()->findLatest($page);
    }

    /**
     * @inheritDoc
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    public function getObjectRepository(): ObjectRepository
    {
        return $this->objectRepository;
    }

    /**
     * @inheritDoc
     */
    public function setObjectRepository(ObjectRepository $objectRepository): void
    {
        $this->objectRepository = $objectRepository;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }
}
