<?php
/**
 * BaseService.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 14, 2020 at 17:13:52
 */

namespace App\Service;

use App\Entity\User;
use App\Pagination\Paginator;
use Doctrine\Persistence\ObjectRepository;

/**
 * Interface ServiceInterface
 * @package App\Service
 */
interface ServiceInterface
{
    /**
     * Retrieves the entity which is associated to the given id.
     * @param int $id
     * @return object|null
     */
    public function get(int $id): ?object;

    /**
     * Retrieves all entities.
     * @return array
     */
    public function getAll(): array;

    /**
     * Gets a page of entities matching the given page number and the optional criteria.
     * @param int $page
     * @param string|null $search
     * @return Paginator
     */
    public function getPage(int $page, ?string $search): Paginator;

    /**
     * Gets the current user.
     * @return User|null
     */
    public function getUser(): ?User;

    /**
     * Sets the current user.
     * @param object|null $user
     */
    public function setUser(?object $user): void;

    /**
     * Gets the object repository.
     * @return ObjectRepository
     */
    public function getObjectRepository(): ObjectRepository;

    /**
     * Sets the object repository.
     * @param ObjectRepository $objectRepository
     */
    public function setObjectRepository(ObjectRepository $objectRepository): void;

    /**
     * Gets the upload directory.
     * @return string
     */
    public function getUploadDirectory(): string;

    /**
     * Sets the upload directory.
     * @param string $uploadDirectory
     */
    public function setUploadDirectory(string $uploadDirectory): void;
}
