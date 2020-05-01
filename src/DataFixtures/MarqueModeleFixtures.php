<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Marque;
use App\Entity\Modele;

class MarqueModeleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arr_marques = array('Peugeot', 'Citroën', 'Audi');

        foreach ($arr_marques as $marque) {
            // création des marques
            $obj_marque = new Marque();
            $obj_marque->setNom($marque);

            $manager->persist($obj_marque);
        }
        // flush après création des marques
        $manager->flush();

        // On cherche les marques créées
        $arr_obj_marques = $manager->getRepository(Marque::class)->findAll();
        foreach ($arr_obj_marques as $obj_marque) {

            if ($obj_marque->getNom() == "Peugeot") {
                // on cherche la marque Peugeot
                $obj_marque_peugeot = $manager->getRepository(Marque::class)->findOneBy(array("nom"=>"Peugeot"));
                $arr_modeles_peugeot = array('308', '208', '508', '2008', '3008');
    
                foreach ($arr_modeles_peugeot as $modelePeugeot) {
                    // création des modéles de la marque Peugeot
                    $obj_modelePeugeot = new Modele();
                    $obj_modelePeugeot->setNom($modelePeugeot);
                    $obj_modelePeugeot->setMarque($obj_marque_peugeot);
    
                    $manager->persist($obj_modelePeugeot);
                }
            }
    
            if ($obj_marque->getNom() == "Citroën") {
                // on cherche la marque Citroën
                $obj_marque_citroen = $manager->getRepository(Marque::class)->findOneBy(array("nom"=>"Citroën"));
                $arr_modeles_citroen = array('C3', 'Berlingo', 'Cactus', 'Mehari', '3008');
    
                foreach ($arr_modeles_citroen as $modeleCitroen) {
                    // création des modéles de la marque Citroen
                    $obj_modeleCitroen = new Modele();
                    $obj_modeleCitroen->setNom($modeleCitroen);
                    $obj_modeleCitroen->setMarque($obj_marque_citroen);
    
                    $manager->persist($obj_modeleCitroen);
                }
            }
    
            if ($obj_marque->getNom() == "Audi") {
                // on cherche la marque Audi
                $obj_marque_audi = $manager->getRepository(Marque::class)->findOneBy(array("nom"=>"Audi"));
                $arr_modeles_audi = array('A1', 'A3', 'A6', 'Q7', 'TT');
    
                foreach ($arr_modeles_audi as $modeleAudi) {
                    // création des modéles de la marque Audi
                    $obj_modeleAudi = new Modele();
                    $obj_modeleAudi->setNom($modeleAudi);
                    $obj_modeleAudi->setMarque($obj_marque_audi);
    
                    $manager->persist($obj_modeleAudi);
                }
            }
        }
        // flush après création des modèles
        $manager->flush();
    }
}
