<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function loadUserByIdentifier(string $identifier): ?User
    {
        $entityManager = $this->getEntityManager();

        // Check if the identifier is an email address
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return $this->findOneBy(['email' => $identifier]);
        }
        if (Uuid::isValid($identifier)) {
            return $this->findOneBy(['uuid' => Uuid::fromString($identifier)->toBinary()]);
        }
        return null;
    }
    
    /**
    * @return User[] Returns an array of User objects
    */
    public function findByInviteBlocked(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.blockedStatus <= :blockedStatus')
            ->setParameter('blockedStatus', 1)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return User[] Returns an array of User objects
    */
    public function findByMessageBlocked(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.blockedStatus <= :blockedStatus')
            ->setParameter('blockedStatus', 2)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
    * @return User[] Returns an array of User objects
    */
    public function findByFullyBlocked(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.blockedStatus >= :blockedStatus')
            ->setParameter('blockedStatus', 3)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return User[] Returns an array of User objects
    */
    public function findFriendByInviteBlocked(User $user): array
    {
        $blockedUsers = $this->findByInviteBlocked();
        $friends = $user->getFriends();
        return array_intersect($blockedUsers, $friends);
    }

    /**
    * @return User[] Returns an array of User objects
    */
    public function findFriendByMessageBlocked(User $user): array
    {
        $blockedUsers = $this->findByMessageBlocked();
        $friends = $user->getFriends();
        return array_intersect($blockedUsers, $friends);
    }

    public function findAllUsersExluding(User $user): array
    {
        $username = $user->getName();
        return $this->createQueryBuilder('u')
            ->andWhere('u.name != :username')
            ->andWhere('u.blockedStatus = 0')
            ->setParameter('username', $username)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return User[] Returns an array of User objects
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

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
