<?php

namespace App\Repository;

use App\Entity\Voiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Voiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voiture[]    findAll()
 * @method Voiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voiture::class);
    }

    // /**
    //  * @return Voiture[] Returns an array of Voiture objects
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
    public function findOneBySomeField($value): ?Voiture
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getWithSearchQueryBuilder(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('v');

        return $qb
            ->where('v.a_vendre = :a_vendre')
            ->setParameter('a_vendre', true)
            ->leftJoin('v.modele', 'modele')
            ->leftJoin('modele.marque', 'marque')
            ->orderBy('marque.nom', 'ASC')
        ;
    } 

    public function findVoitureByCriteres($arr_criteres)
    {
        $qb = $this->createQueryBuilder('v');
        $qb->andWhere('v.a_vendre = :a_vendre')
           ->setParameter('a_vendre', $arr_criteres['a_vendre'])
           ->andWhere('v.estVendue is NULL');

        // marque
        if(array_key_exists('marques', $arr_criteres) && !empty($arr_criteres['marques']) && is_array($arr_criteres['marques'])) {
            $qb->leftJoin('v.modele', 'modele')
               ->leftJoin('modele.marque', 'marque')
               ->andWhere('marque IN (:marques)')
               ->setParameter('marques', $arr_criteres['marques']);
        }

        // garage
        if(array_key_exists('garages', $arr_criteres) && !empty($arr_criteres['garages']) && is_array($arr_criteres['garages'])) {         
            $qb->andWhere('v.garage IN (:garages)')
               ->setParameter('garages', $arr_criteres['garages']);
        }

        // typeCarrosserie
        if(array_key_exists('typesCarroserie', $arr_criteres) && !empty($arr_criteres['typesCarroserie']) && is_array($arr_criteres['typesCarroserie'])) {
            $qb->andWhere('v.typeCarrosserie IN (:typesCarrosserie)')
               ->setParameter('typesCarrosserie', $arr_criteres['typesCarroserie']);
        }

        // carburant
        if(array_key_exists('carburants', $arr_criteres) && !empty($arr_criteres['carburants']) && is_array($arr_criteres['carburants'])) {
            $qb->andWhere('v.carburant IN (:carburants)')
               ->setParameter('carburants', $arr_criteres['carburants']);
        }

        // nbPortes
        if(array_key_exists('nbPortes', $arr_criteres) && !empty($arr_criteres['nbPortes']) && is_array($arr_criteres['nbPortes'])) {
            $qb->andWhere('v.nbPortes IN (:nbPortes)')
               ->setParameter('nbPortes', $arr_criteres['nbPortes']);
        }

        //prix 
        if(array_key_exists('prix', $arr_criteres) && !empty($arr_criteres['prix'])) {
            $qb->andWhere('v.prix < :prix')
               ->setParameter('prix', $arr_criteres['prix']);
        }

        return $qb->getQuery()->getResult();
    }
}
