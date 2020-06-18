<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\DependencyInjection\Container;
use App\Service\ServiceInformations;

use App\Entity\Voiture;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(ServiceInformations $serviceInformations)
    {

        $x = $this->getDoctrine()->getManager()->getRepository(Voiture::class)->findAllBoites();
        //dump($x);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
