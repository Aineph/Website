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
     * The current user.
     * @var User
     */
    private $user;

    /**
     * The object repository.
     * @var ObjectRepository
     */
    private $objectRepository;

    /**
     * The upload directory.
     * @var string
     */
    private $uploadDirectory;

    /**
     * The entity manager.
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * AbstractService constructor.
     * @param string $uploadDirectory
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(string $uploadDirectory, Security $security, EntityManagerInterface $entityManager)
    {
        $this->setUser($security->getUser());
        $this->setUploadDirectory($uploadDirectory);
        $this->setEntityManager($entityManager);
    }

    /**
     * @inheritDoc
     */
    public function get(int $id): ?object
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
    public function getPage(int $page, ?string $search = null): Paginator
    {
        return $this->getObjectRepository()->findLatest($page, $search);
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
    public function setUser(?object $user): void
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
     * @inheritDoc
     */
    public function getUploadDirectory(): string
    {
        return $this->uploadDirectory;
    }

    /**
     * @inheritDoc
     */
    public function setUploadDirectory(string $uploadDirectory): void
    {
        $this->uploadDirectory = $uploadDirectory;
    }

    /**
     * Gets the entity manager.
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * Sets the entity manager.
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }
}
