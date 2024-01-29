<?php

namespace App\Repository;

use App\Entity\GameMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameMedia>
 *
 * @method GameMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameMedia[]    findAll()
 * @method GameMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameMedia::class);
    }

//    /**
//     * @return GameMedia[] Returns an array of GameMedia objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GameMedia
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
