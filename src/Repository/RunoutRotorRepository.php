<?php

namespace App\Repository;

use App\Entity\RunoutRotor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RunoutRotor|null find($id, $lockMode = null, $lockVersion = null)
 * @method RunoutRotor|null findOneBy(array $criteria, array $orderBy = null)
 * @method RunoutRotor[]    findAll()
 * @method RunoutRotor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RunoutRotorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RunoutRotor::class);
    }

    // /**
    //  * @return RunoutRotor[] Returns an array of RunoutRotor objects
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
    public function findOneBySomeField($value): ?RunoutRotor
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
