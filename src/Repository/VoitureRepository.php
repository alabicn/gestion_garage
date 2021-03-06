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

    public function findAllTypesCarrosserie()
    {
        $qb = $this->createQueryBuilder('v');
        $qb->select('v.typeCarrosserie AS typeCarrosserie')
           ->where('v.typeCarrosserie is not NULL')
           ->groupBy('typeCarrosserie');
        
        $arr_result = $qb->getQuery()->getResult();

        $typesCarrosserie = [];
        foreach ($arr_result as $arr_typeCarrosserie) {
            foreach ($arr_typeCarrosserie as $typeCarrosserie) {
                $typesCarrosserie[$typeCarrosserie] = $typeCarrosserie;
            }
        }

        return $typesCarrosserie;
    }

    public function findAllCarburants()
    {
        $qb = $this->createQueryBuilder('v');
        $qb->select('v.carburant AS carburant')
           ->where('v.carburant is not NULL')
           ->groupBy('carburant')
           ->orderBy('carburant', 'ASC');
        
        $arr_result = $qb->getQuery()->getResult();

        $carburants = [];
        foreach ($arr_result as $arr_carburants) {
            foreach ($arr_carburants as $carburant) {
                $carburants[$carburant] = $carburant;
            }
        }

        return $carburants;
    }

    public function findAllBoites()
    {
        $qb = $this->createQueryBuilder('v');
        $qb->select('v.boiteDeVitesse AS boite')
           ->where('v.boiteDeVitesse is not NULL')
           ->groupBy('boite')
           ->orderBy('boite', 'ASC');

        $arr_result = $qb->getQuery()->getResult();

        $boites = [];
        foreach ($arr_result as $arr_boites) {
            foreach ($arr_boites as $boite) {
                $boites[$boite] = $boite;
            }
        }

        return $boites;
    }

    public function findAllNbPortes()
    {
        $qb = $this->createQueryBuilder('v');
        $qb->select('v.nbPortes AS nbPortes')
           ->where('v.nbPortes is not NULL')
           ->groupBy('nbPortes')
           ->orderBy('nbPortes', 'ASC');

        $arr_result = $qb->getQuery()->getResult();

        $nbPortes = [];
        foreach ($arr_result as $arr_nbPortes) {
            foreach ($arr_nbPortes as $nbPorte) {
                $nbPortes[$nbPorte] = $nbPorte;
            }
        }

        return $nbPortes;
    }

    public function findMinMaxPrixDeVoiture()
    {
        $qb = $this->createQueryBuilder('v');
        $qb->select('MIN(v.prix) AS min_prix, MAX(v.prix) AS max_prix');

        return $qb->getQuery()->getOneOrNullResult();
    }

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
    
    public function getWithSearchQueryBuilderAll(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('v');

        return $qb
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

        // modele
        /*if (array_key_exists('modele', $arr_criteres) && !empty($arr_criteres['modele']) && is_array($arr_criteres['modele'])) {
            $qb->andWhere('v.modele IN (:modele)')
               ->setParameter('modele', $arr_criteres['modele'])
               ->andWhere('modele.marque = :marque')
               ->setParameter('marque', $arr_criteres['modele'][0]->getMarque());
        }*/

        // garage
        if (array_key_exists('garages', $arr_criteres) && !empty($arr_criteres['garages']) && is_array($arr_criteres['garages'])) {         
            $qb->andWhere('v.garage IN (:garages)')
               ->setParameter('garages', $arr_criteres['garages']);
        }

        // typeCarrosserie
        if (array_key_exists('typesCarroserie', $arr_criteres) && !empty($arr_criteres['typesCarroserie']) && is_array($arr_criteres['typesCarroserie'])) {
            $qb->andWhere('v.typeCarrosserie IN (:typesCarrosserie)')
               ->setParameter('typesCarrosserie', $arr_criteres['typesCarroserie']);
        }

        // carburant
        if (array_key_exists('carburants', $arr_criteres) && !empty($arr_criteres['carburants']) && is_array($arr_criteres['carburants'])) {
            $qb->andWhere('v.carburant IN (:carburants)')
               ->setParameter('carburants', $arr_criteres['carburants']);
        }

        // boite de vitesse
        if (array_key_exists('boites', $arr_criteres) && !empty($arr_criteres['boites']) && is_array($arr_criteres['boites'])) {
            $qb->andWhere('v.boiteDeVitesse IN (:boites)')
               ->setParameter('boites', $arr_criteres['boites']);
        }

        // nbPortes
        if (array_key_exists('nbPortes', $arr_criteres) && !empty($arr_criteres['nbPortes']) && is_array($arr_criteres['nbPortes'])) {
            $qb->andWhere('v.nbPortes IN (:nbPortes)')
               ->setParameter('nbPortes', $arr_criteres['nbPortes']);
        }

        // options
        if (array_key_exists('options', $arr_criteres) && !empty($arr_criteres['options']) && is_array($arr_criteres['options'])) {
            $qb->leftJoin('v.voitureOptions', 'vo')
               ->leftJoin('vo.option', 'option')
               ->andWhere('option IN (:options)')
               ->setParameter('options', $arr_criteres['options']);
        }

        //prix 
        if (array_key_exists('prix', $arr_criteres) && !empty($arr_criteres['prix'])) {
            $qb->andWhere('v.prix <= :prix')
               ->setParameter('prix', $arr_criteres['prix']);
        }

        return $qb->getQuery()->getResult();
    }
}
