<?php

namespace App\Repository;

use App\Entity\AffectationCompte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AffectationCompte|null find($id, $lockMode = null, $lockVersion = null)
 * @method AffectationCompte|null findOneBy(array $criteria, array $orderBy = null)
 * @method AffectationCompte[]    findAll()
 * @method AffectationCompte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AffectationCompteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AffectationCompte::class);
    }

    // /**
    //  * @return AffectationCompte[] Returns an array of AffectationCompte objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AffectationCompte
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
