<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Garage;

class GarageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $str_france = "France";

        // création des garages en Alsace
        $obj_garage_strasbourg = new Garage();
        $obj_garage_strasbourg->setNom("Garage Strasbourg")
                              ->setNumeroTelephone("03 68 98 50 00")
                              ->setAdresse("1 Parc de l'Étoile")
                              ->setCodePostal("67000")
                              ->setVille("Strasbourg")
                              ->setPays($str_france);

        $manager->persist($obj_garage_strasbourg);
        
        $obj_garage_colmar = new Garage();
        $obj_garage_colmar->setNom("Garage Colmar")
                          ->setNumeroTelephone("03 89 20 68 68")
                          ->setAdresse("1 Place de la Mairie")
                          ->setCodePostal("68000")
                          ->setVille("Colmar")
                          ->setPays($str_france); 

        $manager->persist($obj_garage_colmar);

        $obj_garage_mulhouse = new Garage();
        $obj_garage_mulhouse->setNom("Garage Mulhouse")
                            ->setNumeroTelephone("03 89 32 58 58")
                            ->setAdresse("2 Rue Pierre et Marie Curie")
                            ->setCodePostal("68100")
                            ->setVille("Mulhouse")
                            ->setPays($str_france);

        $manager->persist($obj_garage_mulhouse);
        
        $manager->flush();
    }
}
