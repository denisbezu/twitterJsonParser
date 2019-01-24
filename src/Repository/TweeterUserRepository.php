<?php

namespace App\Repository;

use App\Entity\TweeterUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TweeterUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method TweeterUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method TweeterUser[]    findAll()
 * @method TweeterUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TweeterUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TweeterUser::class);
    }

    // /**
    //  * @return TweeterUser[] Returns an array of TweeterUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TweeterUser
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
