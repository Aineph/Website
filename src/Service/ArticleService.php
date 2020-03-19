<?php
/**
 * ArticleService.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 09, 2020 at 15:43:11
 */

namespace App\Service;

use App\Entity\Article;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Security;

/**
 * Class ArticleService
 * @package App\Service
 */
class ArticleService extends AbstractService implements ServiceInterface
{
    /**
     * @var Article
     */
    private $article;

    /**
     * ArticleService constructor.
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        parent::__construct($security, $entityManager);
        $this->setObjectRepository($this->getEntityManager()->getRepository(Article::class));
        $this->setArticle(new Article());
    }

    /**
     *
     */
    public function create()
    {
        $this->getArticle()->setUser($this->getUser());
        try {
            $this->getArticle()->setDate(new DateTime('now'));
        } catch (Exception $e) {
            $this->getArticle()->setDate(null);
        }
        $this->getEntityManager()->persist($this->getArticle());
        $this->getEntityManager()->flush();
    }

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle(Article $article): void
    {
        $this->article = $article;
    }
}
