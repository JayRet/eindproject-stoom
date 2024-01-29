<?php

namespace App\Repository;

use App\Entity\UserGameData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserGameData>
 *
 * @method UserGameData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGameData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGameData[]    findAll()
 * @method UserGameData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGameDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGameData::class);
    }

//    /**
//     * @return UserGameData[] Returns an array of UserGameData objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserGameData
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
