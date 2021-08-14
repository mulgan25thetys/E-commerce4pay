<?php


namespace App\Core\Product\Repository;

use App\Entity\Playlist;
use App\Infrastructure\Orm\AbstractRepository;
use App\Infrastructure\Orm\IterableQueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
class PlaylistRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    /**
     * @return playlist[]
     */
    public function findAll()
    {
        return $this->createQueryBuilder('p')
            ->where('p.online = true')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return IterableQueryBuilder<Formation>
     */
    public function findRecent(int $limit): IterableQueryBuilder
    {
        return $this->createIterableQuery('p')
            ->where('p.online = true')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit);
    }

}