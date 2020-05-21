<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\VoitureOption;
use App\Entity\Option;
use App\Entity\Voiture;
use App\DataFixtures\VoitureFixtures;
use App\DataFixtures\OptionFixtures;

class VoitureOptionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // options
        for ($i = 1; $i <= rand(40, 50); $i++) {
            $obj_option1 = $manager->getRepository(Option::class)->find(rand(2, 5));
            $obj_voiture1 = $manager->getRepository(Voiture::class)->find($i);

            $obj_voitureOption1 = new VoitureOption();
            $obj_voitureOption1->setOption($obj_option1)
                              ->setVoiture($obj_voiture1)
                              ->setNombre(1);

            $obj_voiture1->setPrix($obj_voiture1->getPrix() + $obj_option1->getPrix());

            $manager->persist($obj_voitureOption1);
            $manager->persist($obj_voiture1);
        }

        // airbag
        $obj_option2 = $manager->getRepository(Option::class)->findOneBy(['title' => "Airbag"]);
        for ($i = 51; $i <= rand(51, 65); $i++) {
            $obj_voiture2 = $manager->getRepository(Voiture::class)->find($i);

            $obj_voitureOption2 = new VoitureOption();
            $obj_voitureOption2->setOption($obj_option2)
                               ->setVoiture($obj_voiture2)
                               ->setNombre(2);
            
            $obj_voiture2->setPrix($obj_voiture2->getPrix() + $obj_option2->getPrix());

            $manager->persist($obj_voitureOption2);
            $manager->persist($obj_voiture2);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            VoitureFixtures::class,
            OptionFixtures::class
        );
    }
}
