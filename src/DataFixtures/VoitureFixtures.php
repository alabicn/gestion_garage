<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\Voiture;
use App\Entity\Modele;
use App\Entity\Garage;

use Faker;
use Faker\Factory;

class VoitureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $carrosseries = ['Berline', 'Cabriolét'];
        $carburants = ['Diesel', 'Essence', 'Hybride'];
        $bdv = ['Manuelle', 'Automatique'];
        $nbPortes = [3, 5];

        // On cherche les modeles créés
        $arr_obj_modeles = $manager->getRepository(Modele::class)->findAll();
        foreach ($arr_obj_modeles as $obj_modele) {
            for ($i = 0;$i <= rand(7, 10);$i++) {

                $dateFabrication = $faker->dateTimeBetween('-10 years', '-2 years', 'Europe/Paris');
                $cloneDateFabrication = clone $dateFabrication;
                $dateVendu = $cloneDateFabrication->modify('+'.rand(1, 2).' months');
                $arr_date_vendu = [null, $dateVendu];
                $arr_avendre = [true, false];

                $obj_voiture = new Voiture();
                $obj_voiture->setImmatriculation(chr(rand(65,90)).chr(rand(65,90))."-".rand(100,999)."-".chr(rand(65,90)).chr(rand(65,90)))
                            ->setDateFabrication($faker->dateTimeBetween('-10 years', '-2 years', 'Europe/Paris'))
                            ->setKilometrage(rand(50000,200000))
                            ->setAVendre($arr_avendre[(rand(0, 1))])
                            ->setEstVendue($arr_date_vendu[rand(0, 1)])
                            ->setTypeCarrosserie($carrosseries[rand(0,1)])
                            ->setCarburant($carburants[rand(0,2)])
                            ->setnbPortes($nbPortes[rand(0,1)])
                            ->setPrix(rand(1000000, 10000000) / 100)
                            ->setBoiteDeVitesse($bdv[rand(0,1)])
                            ->setModele($obj_modele)
                            ->setGarage($manager->getRepository(Garage::class)->find(rand(1,3)));
    
                $manager->persist($obj_voiture);
                unset($dateFabrication);
                unset($cloneDateFabrication);
                unset($dateVendu);
                unset($arr_date_vendu);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ModeleFixtures::class,
        );
    }
}
