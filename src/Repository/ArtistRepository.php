<?php

namespace App\Repository;

use App\Entity\Artist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Artist>
 */
class ArtistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artist::class);
    }

    public function findByStyle(string $style, array $order, int $limit = null, int $offset = null): array
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.style = :style')
            ->setParameter('style', $style);

        $first = true;
        foreach ($order as $field => $direction) {
            if ($first) {
                $qb->orderBy('a.' . $field, $direction);
                $first = false;
            } else {
                $qb->addOrderBy('a.' . $field, $direction);
            }
        }

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        if ($offset !== null) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }

    public function findByStyleWithDQL(string $style, int $limit = null, int $offset = null): array
    {
        $dql = "SELECT a FROM App\Entity\Artist a
                WHERE a.style = :style
                ORDER BY a.mixDate ASC, a.mixTime ASC";
        return $this->getEntityManager()->createQuery($dql)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->setParameter('style', $style)
            ->getResult();
    }

    public function countByStyle(string $style): int
    {
        return (int)$this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.style = :style')
            ->setParameter('style', $style)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getArtistWithInfos(int $nbPerPage, int $offset): Paginator
    {
        $q = $this->createQueryBuilder('a')
            ->orderBy('a.mixDate', 'ASC')
            ->addOrderBy('a.mixTime', 'ASC')
            ->leftJoin('a.musics', 'm')
            ->addSelect('m')
            ->setFirstResult($offset)
            ->setMaxResults($nbPerPage)
            ->getQuery();

        return new Paginator($q);
    }


    //    /**
    //     * @return Wish[] Returns an array of Wish objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Wish
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
