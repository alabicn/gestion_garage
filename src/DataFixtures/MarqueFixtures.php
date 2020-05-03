<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Marque;

class MarqueFixtures extends Fixture
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
    }
}
