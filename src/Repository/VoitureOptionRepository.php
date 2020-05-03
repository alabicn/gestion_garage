<?php

namespace App\Repository;

use App\Entity\VoitureOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VoitureOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoitureOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoitureOption[]    findAll()
 * @method VoitureOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoitureOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoitureOption::class);
    }

    // /**
    //  * @return VoitureOption[] Returns an array of VoitureOption objects
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
    public function findOneBySomeField($value): ?VoitureOption
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
