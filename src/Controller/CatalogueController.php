<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Voiture;

class CatalogueController extends AbstractController
{
    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function index(EntityManagerInterface $em)
    {
        $arr_voitures = $em->getRepository(Voiture::class)->findAll();

        $array['voitures'] = $arr_voitures;
        $array['title'] = 'Catalogue des voitures';

        return $this->render('catalogue/catalogue.html.twig', $array);
    }
}
