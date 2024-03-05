<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
    * @return int Returns an integer of the amount of unread Message objects
    * @method int countUnreadMessages(array $conversations)
    */
    public function countUnreadMessages(array $conversations): int
    {
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->andWhere('m.isRead = false')
            ->andWhere('m.conversation IN (:conversations)')
            ->setParameter('conversations', $conversations)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

//    public function findOneBySomeField($value): ?Message
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
