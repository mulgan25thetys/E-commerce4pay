<?php


namespace App\Core\Product\Repository;

use App\Entity\Animal;
use App\Infrastructure\Orm\AbstractRepository;
use App\Infrastructure\Orm\IterableQueryBuilder;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
class AnimalRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }
    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->where('a.online = true')
            ->orderBy('a.createdAt', 'DESC');
    }

    public function queryAllPremium(): QueryBuilder
    {
        return $this->queryAll()
            ->andWhere('a.premium = :premium OR a.createdAt > NOW()')
            ->setParameter('premium', true);
    }

    /**
     * @return IterableQueryBuilder<Animal>
     */
    public function findRecent(int $limit): IterableQueryBuilder
    {
        return $this->createIterableQuery('a')
            ->where('a.online = true')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($limit);
    }

    public function findTotalDuration(): int
    {
        $resultat= $this->createQueryBuilder('a')
            ->select('SUM(a.duration)')
            ->where('a.online = true')
            ->getQuery()
            ->getSingleScalarResult();
        if(Null !== $resultat) return $resultat;
        return 0;
    }

    /**
     * @return Animal[]
     */
    public function findRandom(int $limit): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('RANDOM()')
            ->where('a.online = true')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}