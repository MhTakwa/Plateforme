<?php

namespace App\Repository;

use App\Entity\VisioConference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VisioConference|null find($id, $lockMode = null, $lockVersion = null)
 * @method VisioConference|null findOneBy(array $criteria, array $orderBy = null)
 * @method VisioConference[]    findAll()
 * @method VisioConference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisioConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VisioConference::class);
    }

    // /**
    //  * @return VisioConference[] Returns an array of VisioConference objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VisioConference
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
