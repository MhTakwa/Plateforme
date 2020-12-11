<?php

namespace App\Repository;

use App\Entity\VideoConference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VideoConference|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoConference|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoConference[]    findAll()
 * @method VideoConference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoConference::class);
    }

    // /**
    //  * @return VideoConference[] Returns an array of VideoConference objects
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
    public function findOneBySomeField($value): ?VideoConference
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
