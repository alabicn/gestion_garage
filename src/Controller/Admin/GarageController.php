<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Marque;
use App\Form\MarqueFormType;
use App\Entity\Modele;
use App\Form\ModeleFormType;

/** @Route("/admin") */
class GarageController extends AbstractController
{
    /**
     * @Route("/addMarque", name="add_marque")
     */
    public function addMarque(Request $obj_request)
    {
        $obj_marque = new Marque();
        $form = $this->createForm(MarqueFormType::class, $obj_marque);
        $form->handleRequest($obj_request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on cherche le manager
            dump($form);
            $manager = $this->getDoctrine()->getManager();
            

            $manager->persist($obj_marque);
            $manager->flush();

            $this->addFlash('success',"Vous avez ajouté la nouvelle marque ".$obj_marque->getNom()." dans votre garage");

            return $this->redirectToRoute('home');
        }

        $array['addMarqueForm'] = $form->createView();
        $array['title'] = 'Ajout de la nouvelle marque';


        return $this->render('admin/garage/addMarque.html.twig', $array);
    }

    /**
     * @Route("/addModele", name="add_modele")
     */
    public function addModele(Request $obj_request)
    {
        $obj_modele = new Modele();
        $form = $this->createForm(ModeleFormType::class, $obj_modele);
        $form->handleRequest($obj_request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on cherche le manager
            $manager = $this->getDoctrine()->getManager();

            $manager->persist($obj_modele);
            $manager->flush();

            $this->addFlash('success',"Vous avez ajouté le nouveau modele ".$obj_modele->getNom()." de la marque ".$obj_modele->getMarque()->getNom()." dans votre garage");

            return $this->redirectToRoute('home');
        }

        $array['addModeleForm'] = $form->createView();
        $array['title'] = 'Ajout du nouveau modèle';


        return $this->render('admin/garage/addModele.html.twig', $array);
    }
}
