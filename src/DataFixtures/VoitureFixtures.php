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
        $carrosserie = ['Berline', 'Cabriolet', 'Coupé', 'Monospace'];

        // On cherche les modeles créés
        $arr_obj_modeles = $manager->getRepository(Modele::class)->findAll();
        foreach ($arr_obj_modeles as $obj_modele) {
            for ($i = 0;$i <= mt_rand(7, 10);$i++) {

                $obj_voiture = new Voiture();
                $obj_voiture->setImmatriculation(chr(rand(65,90)).chr(mt_rand(65,90))."-".mt_rand(100,999)."-".chr(mt_rand(65,90)).chr(mt_rand(65,90)))
                            ->setDateFabrication($faker->dateTimeBetween('-10 years', '-2 years', 'Europe/Paris'))
                            ->setKilometrage(mt_rand(50000,200000))
                            ->setAVendre(true)
                            ->setTypeCarrosserie($carrosserie[mt_rand(0,3)])
                            ->setNbPortes(5)
                            ->setPrix(mt_rand(1000000, 10000000) / 100)
                            ->setEtat('Bon état')
                            ->setModele($obj_modele)
                            ->setGarage($manager->getRepository(Garage::class)->find(mt_rand(1,3)));
    
                $manager->persist($obj_voiture);
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
