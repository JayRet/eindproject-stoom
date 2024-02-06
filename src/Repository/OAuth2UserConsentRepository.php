<?php

namespace App\Repository;

use App\Entity\OAuth2UserConsent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OAuth2UserConsent>
 *
 * @method OAuth2UserConsent|null find($id, $lockMode = null, $lockVersion = null)
 * @method OAuth2UserConsent|null findOneBy(array $criteria, array $orderBy = null)
 * @method OAuth2UserConsent[]    findAll()
 * @method OAuth2UserConsent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OAuth2UserConsentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OAuth2UserConsent::class);
    }

//    /**
//     * @return OAuth2UserConsent[] Returns an array of OAuth2UserConsent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OAuth2UserConsent
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
