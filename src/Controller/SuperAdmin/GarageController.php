<?php

namespace App\Controller\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\Garage;
use App\Form\GarageFormType;

/** @Route("/sadmin") */
class GarageController extends AbstractController
{
     /**
     * @Route("/garages", name="garages")
     */
    public function indexAction(EntityManagerInterface $em)
    {            
        $arr_garages = $em->getRepository(Garage::class)->findAll();

        uasort($arr_garages, function($a, $b) {
            return strnatcmp($a->getCodePostal(), $b->getCodePostal());
        });

        $array['garages'] = $arr_garages;
        $array['title'] = 'Liste des garages';
    
        return $this->render('superadmin/garage/garages.html.twig', $array);
    }

     /**
     * @Route("/garage/add", name="garage_add")
     */
    public function addAction(Request $obj_request)
    {
        $obj_garage = new Garage();
        $form = $this->createForm(GarageFormType::class, $obj_garage);
        $form->handleRequest($obj_request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on cherche le manager
            $em = $this->getDoctrine()->getManager();

            $formVille = $form['ville']->getData();
            $ville = explode("(", $formVille);

            $str_ville = $ville[0];
            $str_codePostal = substr($ville[1], 0, -1);

            $obj_garage->setVille($str_ville);
            $obj_garage->setCodePostal($str_codePostal);
            $obj_garage->setPays("France");

            
            $em->persist($obj_garage);
            $em->flush();

            $this->addFlash('success', "Vous avez créé la nouvelle garage");
            return $this->redirectToRoute('garages');
        }

        $array['title'] = "Ajout de la nouvelle garage";
        $array['addGarageForm'] = $form->createView();

        return $this->render('superadmin/garage/addGarage.html.twig', $array);;
    }

    /**
     * @Route("/garage/edit/{id}", name="garage_edit")
     */
    public function editAction(Garage $garage, Request $obj_request)
    {
        $em = $this->getDoctrine()->getManager();
        $obj_garage = $em->getRepository(Garage::class)->find($garage->getId());
        $form = $this->createForm(GarageFormType::class, $obj_garage);
        $form->handleRequest($obj_request);

        $ville = clone $garage->getVille();
        if ($form->isSubmitted() && $form->isValid()) {
            $obj_garage->setVille($ville);

            $em->persist($obj_garage);
            $em->flush();

            $this->addFlash('success', "Vos modifications ont bien été enregistrées");
            return $this->redirectToRoute('garages');
        }

        $array['title'] = "Modification de la garage ".$obj_garage->getNom();
        $array['editGarageForm'] = $form->createView();

        return $this->render('superadmin/garage/editGarage.html.twig', $array);
    }
}
