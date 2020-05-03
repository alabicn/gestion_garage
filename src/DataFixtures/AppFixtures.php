<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Utilisateur;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // création de superadmin 
        $obj_superAdmin = new Utilisateur();
        $obj_superAdmin->setEmail("superadmin@gmail.com")
                       ->setPseudo("super_admin")
                       ->setRoles(['ROLE_SUPER_ADMIN'])
                       ->setPassword($this->encoder->encodePassword($obj_superAdmin, "superadmin987"))
                       ->setNeedRGPD(new \DateTime)
                       ->setEstActive(true);
        
        $manager->persist($obj_superAdmin);

        $obj_admin = new Utilisateur();
        $obj_admin->setEmail("admin@gmail.com")
                  ->setPseudo("admin")
                  ->setRoles(['ROLE_ADMIN'])
                  ->setPassword($this->encoder->encodePassword($obj_admin, "admin987"))
                  ->setNeedRGPD(new \DateTime)
                  ->setEstActive(true);

        $manager->persist($obj_admin);

        $manager->flush();
    }
}
