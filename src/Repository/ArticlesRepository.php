<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

class ArticlesRepository extends ServiceEntityRepository
{
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Articles::class);
        $this->paginator = $paginator;
    }

    public function findPaginatedArticles(int $page, int $limit)
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        return $this->paginator->paginate(
            $queryBuilder,
            $page,
            $limit
        );
    }
}
