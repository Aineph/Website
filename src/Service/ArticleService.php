<?php
/**
 * ArticleService.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 09, 2020 at 15:43:11
 */

namespace App\Service;

use App\Entity\Article;
use App\Entity\Message;
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
     * The current article.
     * @var Article
     */
    private $article;

    /**
     * ArticleService constructor.
     * @param string $uploadDirectory
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(string $uploadDirectory, Security $security, EntityManagerInterface $entityManager)
    {
        parent::__construct($uploadDirectory, $security, $entityManager);
        $this->setObjectRepository($this->getEntityManager()->getRepository(Article::class));
        $this->setArticle(new Article());
    }

    /**
     * Updates the current article.
     */
    public function update(): void
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
     * Deletes the current article.
     */
    public function delete(): void
    {
        if ($this->getArticle()) {
            $this->getEntityManager()->remove($this->getArticle());
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Gets the current article.
     * @return Article|null
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }

    /**
     * Sets the current article.
     * @param Article|null $article
     */
    public function setArticle(?Article $article): void
    {
        $this->article = $article;
    }
}
