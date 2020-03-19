<?php
/**
 * ProjectRepository.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 18, 2020 at 21:53:33
 */

namespace App\Repository;

use App\Entity\Project;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ProjectRepository
 * @package App\Repository
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    /**
     * ProjectRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @param int $page
     * @return Paginator
     */
    public function findLatest(int $page)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $paginator = new Paginator($queryBuilder);

        return $paginator->paginate($page);
    }
}
