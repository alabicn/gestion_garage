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
            ->orderBy('v.prix', 'ASC')
        ;
    } 

    public function findVoitureByCriteres($arr_critere, $select=Array())
    {
        $qb = $this->createQueryBuilder('v');

        if(count($select) == 0) $select[] = "v";

        $qb->select(implode(", ",$select));
        dump($arr_critere);


        // Marques
        if(array_key_exists('marques', $arr_critere) && !empty($arr_critere['marques']) && is_array($arr_critere['marques'])) {
            $qb->andWhere($qb->expr()->in('e.classe',':classes'))
               ->setParameter('classes',$arr_critere['classes']);
        }

        // Modeles
        if(array_key_exists('modeles',$arr_critere) && !empty($arr_critere['modeles']) && is_array($arr_critere['modeles'])) {         
            $qb->leftjoin('v.modele', 'modele');
            $qb->andWhere($qb->expr()->in('modele.id',':modele'))
                ->setParameter('modeles',$arr_critere['modeles']);
        }

        // Nom et prénom
        /*if(array_key_exists('np1',$arr_critere) && $arr_critere['np1']!="")
            $qb ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->andX(
                        $qb->expr()->like('e.prenom',':np1'),
                        $qb->expr()->like('e.nom', ':np2')
                    ),
                    $qb->expr()->andX(
                        $qb->expr()->like('e.prenom',':np2'),
                        $qb->expr()->like('e.nom', ':np1')
                    ),
                    $qb->expr()->like('e.prenom',':np3'),
                    $qb->expr()->like('e.nom', ':np3'),
                    $qb->expr()->like('e.prenom',':np4'),
                    $qb->expr()->like('e.nom', ':np4'),
                    $qb->expr()->like('e.username', ':np5')
                )
            )
                ->setParameter('np1','%'.$arr_critere['np1'].'%')
                ->setParameter('np2','%'.$arr_critere['np2'].'%')
                ->setParameter('np3','%'.$arr_critere['np1']."%".$arr_critere['np2'].'%')
                ->setParameter('np4','%'.$arr_critere['np2']."%".$arr_critere['np1'].'%')
                ->setParameter('np5','%'.$arr_critere['np1'].'%');

        // Code barres
        if(array_key_exists('cb',$arr_critere) && $arr_critere['cb']!="")
            $qb ->andWhere('e.id = :cb')
                ->setParameter('cb', $arr_critere['cb']);

        // Y compris les archivés

        // Pas fan du 'OU' , mais faudrait utiliser boolval() afin de convertir tous les string "false" ou "true" en vrai booléen (en str à cause du js) #2107
        if(array_key_exists('ar',$arr_critere) && ($arr_critere['ar']=="false" || $arr_critere['ar'] == false)) {
            $qb ->andWhere('e.enabled = true');
        }

        if(array_key_exists('sansClasse',$arr_critere) && $arr_critere['sansClasse']=="true") {
            $qb ->andWhere('e.classe is null');
        }

        if (array_key_exists('sansClasse', $arr_critere) && $arr_critere['sansClasse'] == "false")
        {
            $qb ->andWhere('e.classe is not null');
        }

        // Facturation nécessaire
        if (array_key_exists('fa',$arr_critere) && $arr_critere['fa']=="true") {
            $qb ->andWhere('a.penalite != 0');
            $qb ->andWhere('a.facture is NULL');
        }

        // Factures non acquittées
        if (array_key_exists('fa_non_acquit',$arr_critere) && $arr_critere['fa_non_acquit']=="true") {
            $qb ->andWhere('a.facture is not NULL');
            $qb ->leftjoin('a.facture', 'fact', Join::WITH);
            $qb ->andWhere('fact.is_acquittee is NULL OR fact.is_acquittee = false OR fact.is_acquittee = 0');
        }

        // Avec attribution
        if (array_key_exists('aa',$arr_critere) && $arr_critere['aa']=="true") {
            $qb ->andWhere($qb->expr()->isNotNull('a.id'));
            $qb ->andWhere($qb->expr()->isNull('a.dateRestitution'));
        }

        // Avec affectation
        if (array_key_exists('aan', $arr_critere) && $arr_critere['aan'] == "true") {
            $qb ->having('nbAffect > 0');
        } elseif (array_key_exists('san', $arr_critere) && $arr_critere['san'] == "true")
            $qb ->having('nbAffect = 0');

        // Recherche parmi une liste d'élèves
        if(array_key_exists('utilisateursParmi',$arr_critere) && count($arr_critere['utilisateursParmi']) > 0 && is_array($arr_critere['utilisateursParmi']))
        {
            $qb->andWhere($qb->expr()->in('e',':eleves'))
                ->setParameter('eleves',$arr_critere['utilisateursParmi']);

        }

        $qb->orderBy('e.nom','ASC');
        $qb->groupBy('e');
        $qb->addOrderBy('e.prenom','ASC');*/

        dump($qb->getQuery()->getResult());
        return $qb->getQuery()->getResult();

    }
}
