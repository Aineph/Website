<?php
/**
 * ArticleRepository.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 09, 2020 at 19:42:46
 */

namespace App\Repository;

use App\Entity\Article;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ArticleRepository
 * @package App\Repository
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     * ArticleRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Finds the latest articles.
     * @param int $page
     * @param string|null $search
     * @return Paginator
     */
    public function findLatest(int $page, ?string $search = null)
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->where('a.title LIKE :search OR a.content LIKE :search')
            ->orderBy('a.date', 'DESC')
            ->setParameter('search', '%' . $search . '%');
        $paginator = new Paginator($queryBuilder);

        return $paginator->paginate($page);
    }
}
