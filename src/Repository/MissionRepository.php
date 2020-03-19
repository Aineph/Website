<?php
/**
 * MissionRepository.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 15, 2020 at 17:53:22
 */

namespace App\Repository;

use App\Entity\Mission;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class MissionRepository
 * @package App\Repository
 * @method Mission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mission[]    findAll()
 * @method Mission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissionRepository extends ServiceEntityRepository
{
    /**
     * MissionRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mission::class);
    }

    /**
     * @param int $page
     * @return Paginator
     */
    public function findLatest(int $page)
    {
        $queryBuilder = $this->createQueryBuilder('m');
        $paginator = new Paginator($queryBuilder);

        return $paginator->paginate($page);
    }

    /**
     * @param int $user
     * @return array
     */
    public function findLatestFor(int $user): array
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->leftJoin('m.user', 'u')
            ->where('u = :user')
            ->orderBy('m.date', 'DESC')
            ->setParameter('user', $user)
            ->setMaxResults(Paginator::PAGE_SIZE);

        return $queryBuilder->getQuery()->getResult();
    }
}
