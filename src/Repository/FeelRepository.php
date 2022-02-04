<?php

namespace App\Repository;

use App\Entity\Feel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Feel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feel[]    findAll()
 * @method Feel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feel::class);
    }

    // /**
    //  * @return Feel[] Returns an array of Feel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Feel
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
