<?php


namespace App\Core\Product\Repository;

use App\Entity\Product;
use App\Infrastructure\Orm\AbstractRepository;
use App\Infrastructure\Orm\IterableQueryBuilder;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
class ProductRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.online = true')
            ->orderBy('p.createdAt', 'DESC');
    }

    public function queryAllPremium(): QueryBuilder
    {
        return $this->queryAll()
            ->andWhere('a.premium = :premium OR a.createdAt > NOW()')
            ->setParameter('premium', true);
    }

    /**
     * @return IterableQueryBuilder<Product>
     */
    public function findRecent(int $limit): IterableQueryBuilder
    {
        return $this->createIterableQuery('p')
            ->where('p.online = true')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit);
    }

    public function findTotalDuration(): int
    {
        return $this->createQueryBuilder('a')
            ->select('SUM(a.duration)')
            ->where('a.online = true')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return Product[]
     */
    public function findRandom(int $limit): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('RANDOM()')
            ->where('p.online = true')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}