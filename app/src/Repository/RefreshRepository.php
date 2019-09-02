<?php

namespace App\Repository;

use App\Entity\Refresh;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Refresh|null find($id, $lockMode = null, $lockVersion = null)
 * @method Refresh|null findOneBy(array $criteria, array $orderBy = null)
 * @method Refresh[]    findAll()
 * @method Refresh[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefreshRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Refresh::class);
    }

    // /**
    //  * @return Refresh[] Returns an array of Refresh objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Refresh
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
