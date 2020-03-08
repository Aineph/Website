<?php
/**
 * MessageRepository.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 23, 2020 at 13:09:03
 */

namespace App\Repository;

use App\Entity\Message;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class MessageRepository
 * @package App\Repository
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    /**
     * MessageRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * @param int $page
     * @return Paginator
     */
    public function findLatest(int $page)
    {
        $queryBuilder = $this->createQueryBuilder('m')->orderBy('m.date', 'DESC');
        $paginator = new Paginator($queryBuilder);
        return $paginator->paginate($page);
    }
}
