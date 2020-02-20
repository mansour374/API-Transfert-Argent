<?php

namespace App\Repository;

use App\Entity\TermeContrat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ThermeContrat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThermeContrat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThermeContrat[]    findAll()
 * @method ThermeContrat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TermeContratRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TermeContrat::class);
    }

    // /**
    //  * @return TermeContrat[] Returns an array of TermeContrat objects
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
    public function findOneBySomeField($value): ?TermeContrat
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
