<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Option;

class OptionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arr_options = ['GPS', 'Airbag', 'Climatisation', 'Radio digitale', 'Caméra de recul'];

        foreach ($arr_options as $option) {
            // création des options
            $obj_option = new Option();
            $obj_option->setTitle($option)
                       ->setPrix(mt_rand(7451, 28911) / 100);

            $manager->persist($obj_option);
        }
        $manager->flush();
    }
}
