<?php

namespace App\Service;

use App\Repository\NewsRepository;
use App\Entity\News as NewsEntity;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class News
{
    /** @var NewsRepository */
    private $newsRepository;
    /** @var PaginatorInterface */
    private $paginator;

    /**
     * @param NewsRepository $newsRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        NewsRepository $newsRepository,
        PaginatorInterface $paginator
    ) {
        $this->newsRepository = $newsRepository;
        $this->paginator = $paginator;
    }

    /**
     * @return NewsEntity
     * @throws NonUniqueResultException
     */
    public function getLastNews(): NewsEntity
    {
        return $this->newsRepository->getLastNews();
    }

    /**
     * @param int $limit
     * @param int $page
     * @return Iterable|News[]
     */
    public function getNews(int $limit, int $page = 1): SlidingPaginationInterface
    {
        $query = $this->newsRepository->getNewsQB();
        return $this->paginator
            ->paginate($query, $page, $limit);
    }
}
