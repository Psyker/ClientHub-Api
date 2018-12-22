<?php

namespace App\Repository;

use App\Entity\Referrer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Referrer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referrer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referrer[]    findAll()
 * @method Referrer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferrerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Referrer::class);
    }

    // /**
    //  * @return Referrer[] Returns an array of Referrer objects
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
    public function findOneBySomeField($value): ?Referrer
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
