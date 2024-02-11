<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
    * @return Game[] Returns an array of Game objects
    * @method Game[] findAllVisible(User $user)
    */
    public function findAllVisible(User $user): array
    {
        $friends = $user->getFriends();
        return $this->createQueryBuilder('g')
            ->orWhere('g.isPublic = true')
            ->orWhere('g.creator = :user')
            ->orWhere($this->createQueryBuilder('g')->expr()->in('g.creater = :friends')) // QUESTION: is this correct?
            ->setParameter('user', $user)
            ->setParameter('friends', $friends)
            ->orderBy('g.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

}
