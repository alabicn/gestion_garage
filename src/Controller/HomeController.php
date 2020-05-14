<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\DependencyInjection\Container;
use App\Service\ServiceInformations;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(ServiceInformations $serviceInformations)
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
