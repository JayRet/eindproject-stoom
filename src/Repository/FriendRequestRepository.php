<?php

namespace App\Repository;

use App\Entity\FriendRequest;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Friend>
 *
 * @method Friend|null find($id, $lockMode = null, $lockVersion = null)
 * @method Friend|null findOneBy(array $criteria, array $orderBy = null)
 * @method Friend[]    findAll()
 * @method Friend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FriendRequest::class);
    }

    /**
    * @return Friend[] Returns an array of Friend objects
    */
    public function alreadyExists(User $user, User $requestedFriend): bool
    {
        $users = array($user, $requestedFriend);

        $result = $this->createQueryBuilder('f')
            ->andWhere('f.sender IN (:users)')
            ->andWhere('f.receiver IN (:users)')
            ->setParameter('users', $users)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;

        if ($result == [] || $result == null) {
            return false;
        }
        return true;
    }

//    public function findOneBySomeField($value): ?Friend
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
