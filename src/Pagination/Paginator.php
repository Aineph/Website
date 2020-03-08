<?php
/**
 * Paginator.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 28, 2020 at 06:42:35
 */

namespace App\Pagination;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Exception;
use Traversable;

/**
 * Class Paginator
 * @package App\Pagination
 */
class Paginator
{
    /**
     * The maximum number of results within a page.
     *
     * @var int
     */
    private const PAGE_SIZE = 10;

    /**
     * The query builder.
     *
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * The index of the current page.
     *
     * @var int
     */
    private $currentPage;

    /**
     * The size of the current page.
     *
     * @var int
     */
    private $pageSize;

    /**
     * The results of the query.
     *
     * @var Traversable
     */
    private $results;

    /**
     * The index of the first result.
     *
     * @var int
     */
    private $firstResult;

    /**
     * The index of the last result.
     *
     * @var int
     */
    private $lastResult;

    /**
     * The number of results.
     *
     * @var int
     */
    private $resultsCount;


    /**
     * Paginator constructor.
     * @param QueryBuilder $queryBuilder
     * @param int $pageSize
     */
    public function __construct(QueryBuilder $queryBuilder, int $pageSize = self::PAGE_SIZE)
    {
        $this->setQueryBuilder($queryBuilder);
        $this->setCurrentPage(0);
        $this->setPageSize($pageSize);
        $this->setResults(null);
        $this->setFirstResult(0);
        $this->setLastResult(0);
        $this->setResultsCount(0);
    }

    /**
     * Performs an SQL query and displays the results that belong to the requested page.
     *
     * @param int $page
     * @return $this
     */
    public function paginate(int $page = 1)
    {
        $this->setCurrentPage(max(1, $page));
        $this->setFirstResult(($this->getCurrentPage() - 1) * $this->getPageSize());
        $query = $this->getQueryBuilder()->setFirstResult($this->getFirstResult())
            ->setMaxResults($this->getPageSize())->getQuery();
        $paginator = new DoctrinePaginator($query, true);

        try {
            $this->setResults($paginator->getIterator());
            $this->setResultsCount($paginator->count());
            $this->setLastResult(min($this->getFirstResult() + $this->getPageSize(), $this->getResultsCount()));
            $this->setFirstResult($this->getFirstResult() + 1);
        } catch (Exception $e) {
            // TODO: Handle SQL Error.
        }
        return $this;
    }

    /**
     * Returns the last page.
     *
     * @return int
     */
    public function getLastPage(): int
    {
        return ceil($this->getResultsCount() / $this->getPageSize());
    }

    /**
     * Checks if there is a previous page.
     *
     * @return bool
     */
    public function hasPreviousPage(): bool
    {
        return $this->getCurrentPage() > 1;
    }

    /**
     * Checks if there is a next page.
     *
     * @return bool
     */
    public function hasNextPage(): bool
    {
        return $this->getCurrentPage() < $this->getLastPage();
    }

    /**
     * Returns the previous page.
     *
     * @return int
     */
    public function getPreviousPage(): int
    {
        return max(1, $this->getCurrentPage() - 1);
    }

    /**
     * Returns the next page.
     *
     * @return int
     */
    public function getNextPage(): int
    {
        return min($this->getLastPage(), $this->getCurrentPage() + 1);
    }

    /**
     * Returns the query builder.
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * Sets the query builder.
     *
     * @param QueryBuilder $queryBuilder
     */
    public function setQueryBuilder(QueryBuilder $queryBuilder): void
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Returns the index of the current page.
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Sets the index of the current page.
     *
     * @param int $currentPage
     */
    public function setCurrentPage(int $currentPage): void
    {
        $this->currentPage = $currentPage;
    }

    /**
     * Returns the page size.
     *
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * Sets the page size.
     *
     * @param int $pageSize
     */
    public function setPageSize(int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }

    /**
     * Returns the query results.
     *
     * @return Traversable
     */
    public function getResults(): ?Traversable
    {
        return $this->results;
    }

    /**
     * Sets the query results.
     *
     * @param Traversable $results
     */
    public function setResults(?Traversable $results): void
    {
        $this->results = $results;
    }

    /**
     * Returns the index of the first result.
     *
     * @return int
     */
    public function getFirstResult(): int
    {
        return $this->firstResult;
    }

    /**
     * Sets the index of the first result.
     *
     * @param int $firstResult
     */
    public function setFirstResult(int $firstResult): void
    {
        $this->firstResult = $firstResult;
    }

    /**
     * Returns the index of the last result.
     *
     * @return int
     */
    public function getLastResult(): int
    {
        return $this->lastResult;
    }

    /**
     * Sets the index of the last result.
     *
     * @param int $lastResult
     */
    public function setLastResult(int $lastResult): void
    {
        $this->lastResult = $lastResult;
    }

    /**
     * Returns the number of results.
     *
     * @return int
     */
    public function getResultsCount(): int
    {
        return $this->resultsCount;
    }

    /**
     * Sets the number of results.
     *
     * @param int $resultsCount
     */
    public function setResultsCount(int $resultsCount): void
    {
        $this->resultsCount = $resultsCount;
    }
}
