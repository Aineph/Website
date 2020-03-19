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
     * @param int $id
     * @return object
     */
    public function get(int $id): object;

    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param int $page
     * @return Paginator
     */
    public function getPage(int $page): Paginator;

    /**
     * @return User|null
     */
    public function getUser(): ?User;

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void;

    /**
     * @return ObjectRepository
     */
    public function getObjectRepository(): ObjectRepository;

    /**
     * @param ObjectRepository $objectRepository
     */
    public function setObjectRepository(ObjectRepository $objectRepository): void;
}
