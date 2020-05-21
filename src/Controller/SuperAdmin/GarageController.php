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

            $garage_exists = count($em->getRepository(Garage::class)->findBy(['nom' => $obj_garage->getNom(), 'numeroTelephone' => $obj_garage->getNumeroTelephone(), 'adresse' => $obj_garage->getAdresse(), 'code_postal' => $str_codePostal, 'ville' => $str_ville, 'pays' => 'France'])) > 0 ? true : false;

            if ($garage_exists) {  

                $this->addFlash('error', "La garage ".$obj_garage->getNom()." dont l'adresse ".$obj_garage->getAdresse().", ".$obj_garage->getCodePostal()." ".$obj_garage->getVille()." est déjà enregistrée.");
                return $this->redirectToRoute('garage_add', ['id' => $obj_marque->getId()]);
            } else {
                
                $obj_garage->setVille($str_ville)
                           ->setCodePostal($str_codePostal)
                           ->setPays("France")
                           ->setEstFerme(false);
            
                $em->persist($obj_garage);
                $em->flush();

                $this->addFlash('success', "Vous avez créé la nouvelle garage.");
                return $this->redirectToRoute('garages');
            }
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

        if ($form->isSubmitted() && $form->isValid()) {

            $garage_exists = count($em->getRepository(Garage::class)->findBy(['nom' => $obj_garage->getNom(), 'numeroTelephone' => $obj_garage->getNumeroTelephone(), 'adresse' => $obj_garage->getAdresse(), 'code_postal' => $obj_garage->getCodePostal(), 'ville' => $obj_garage->getVille(), 'pays' => 'France'])) > 0 ? true : false;

            if ($garage_exists) {  
                             
                $this->addFlash('error', "La garage ".$obj_garage->getNom()." dont l'adresse ".$obj_garage->getAdresse().", ".$obj_garage->getCodePostal()." ".$obj_garage->getVille()." est déjà enregistrée.");
                return $this->redirectToRoute('garage_edit', ['id' => $obj_garage->getId()]);
            } else {
            
                $em->persist($obj_garage);
                $em->flush();

                $this->addFlash('success', "Vos modifications ont bien été enregistrées.");
                return $this->redirectToRoute('garages');
            };
        }

        $array['title'] = "Modification de la garage ".$obj_garage->getNom();
        $array['editGarageForm'] = $form->createView();

        return $this->render('superadmin/garage/editGarage.html.twig', $array);
    }

    /**
     * @Route("/garage/open/{id}", name="garage_open")
     */
    public function openAction(Garage $garage)
    {
        $em = $this->getDoctrine()->getManager();
        $obj_garage = $em->getRepository(Garage::class)->find($garage->getId());

        $obj_garage->setEstFerme(false);

        $em->persist($obj_garage);
        $em->flush();

        $this->addFlash('info', $obj_garage->getNom()." est maintenant ouverte");

        return $this->redirectToRoute('garages');
    }

    /**
     * @Route("/garage/close/{id}", name="garage_close")
     */
    public function closeAction(Garage $garage)
    {
        $em = $this->getDoctrine()->getManager();
        $obj_garage = $em->getRepository(Garage::class)->find($garage->getId());

        $obj_garage->setEstFerme(true);

        $em->persist($obj_garage);
        $em->flush();

        $this->addFlash('warning', $obj_garage->getNom()." est maintenant fermée");

        return $this->redirectToRoute('garages');
    }
}
