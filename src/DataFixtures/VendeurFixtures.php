<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;
use App\Service\ServiceInformations;

use App\Entity\Vendeur;
use App\Entity\Garage;
use Faker\Factory;

class VendeurFixtures extends Fixture
{
    private $encoder;

    private $serviceInformations;

    public function __construct(UserPasswordEncoderInterface $encoder, ServiceInformations $serviceInformations)
    {
        $this->encoder = $encoder;
        $this->serviceInformations = $serviceInformations;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $arr_garages = $manager->getRepository(Garage::class)->findAll();

        foreach ($arr_garages as $garage) {
            for ($i = 0;$i <= mt_rand(2, 5);$i++) {
                // création des vendeurs dans garages
                $str_nom = $faker->firstNameMale();
                $str_prenom = $faker->lastName();

                $obj_vendeur = new Vendeur();
                $obj_vendeur->setNom($str_nom)
                            ->setPrenom($str_prenom)
                            ->setDdn($faker->dateTimeBetween('-60 years', '-25 years', 'Europe/Paris'))
                            ->setGenre("M")
                            ->setGarage($garage)
                            ->setEmail(strtolower($this->serviceInformations->replaceAccent($str_prenom)).".".strtolower($this->serviceInformations->replaceAccent($str_nom))."@gmail.com")
                            ->setPseudo(strtolower($this->serviceInformations->replaceAccent($str_prenom[0])).".".strtolower($this->serviceInformations->replaceAccent($str_nom)))
                            ->setPassword($this->encoder->encodePassword($obj_vendeur, $str_nom."1234"))
                            ->setRoles(['ROLE_USER'])
                            ->setNeedRGPD($faker->dateTimeThisDecade('now', null))
                            ->setEstActive(true);
                
                $manager->persist($obj_vendeur);
                
                unset($str_nom);
                unset($str_prenom);
            }

            for ($i = 0;$i <= mt_rand(1, 4);$i++) {
                // création des vendeuses dans garages
                $str_nom = $faker->firstNameFemale();
                $str_prenom = $faker->lastName();

                $obj_vendeuse = new Vendeur();
                $obj_vendeuse->setNom($str_nom)
                             ->setPrenom($str_prenom)
                             ->setDdn($faker->dateTimeBetween('-60 years', '-25 years', 'Europe/Paris'))
                             ->setGenre("F")
                             ->setGarage($garage)
                             ->setEmail(strtolower($this->serviceInformations->replaceAccent($str_prenom)).".".strtolower($this->serviceInformations->replaceAccent($str_nom))."@gmail.com")
                             ->setPseudo(strtolower($this->serviceInformations->replaceAccent($str_prenom[0])).".".strtolower($this->serviceInformations->replaceAccent($str_nom)))
                             ->setPassword($this->encoder->encodePassword($obj_vendeur, $str_nom."1234"))
                             ->setRoles(['ROLE_USER'])
                             ->setNeedRGPD($faker->dateTimeThisDecade('now', 'Europe/Paris'))
                             ->setEstActive(true);

                $manager->persist($obj_vendeuse);

                unset($str_nom);
                unset($str_prenom);
            }
        }
        $manager->flush();
    }
}
