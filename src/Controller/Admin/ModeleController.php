<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\Marque;
use App\Form\MarqueFormType;
use App\Entity\Modele;
use App\Form\ModeleFormType;

/** @Route("/admin") */
class ModeleController extends AbstractController
{
    /**
     * @Route("/modeles", name="modeles")
     */
    public function indexAction(EntityManagerInterface $em)
    {            
        $arr_modeles = $em->getRepository(Modele::class)->findAll();

        uasort($arr_modeles, function($a, $b) {
            return strnatcmp($a->getMarque()->getNom(), $b->getMarque()->getNom());
        });

        $array['modeles'] = $arr_modeles;
        $array['title'] = 'Liste des modeles';
    
        return $this->render('admin/modele/modeles.html.twig', $array);
    }

    /**
     * @Route("/modele/add", name="modele_add")
     */
    public function addAction(Request $obj_request)
    {
        $obj_modele = new Modele();
        $form = $this->createForm(ModeleFormType::class, $obj_modele);
        $form->handleRequest($obj_request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on cherche le manager
            $em = $this->getDoctrine()->getManager();
            $modele_exists = !empty($em->getRepository(Modele::class)->findBy(['nom' => $obj_modele->getNom(), 'marque' => $obj_modele->getMarque()])) ? true : false;

            if ($modele_exists) {               
                $this->addFlash('error', "Le modele ".$obj_modele->getMarque()->getNom()." ".$obj_modele->getNom()." est déjà enregistré.");
                return $this->redirectToRoute('modele_add');
            } else {
                $em->persist($obj_modele);
                $em->flush();
                $this->addFlash('success', "Vous avez ajouté le nouveau modele ".$obj_modele->getMarque()->getNom()." ".$obj_modele->getNom()." dans votre garage.");
                return $this->redirectToRoute('modeles');
            }
        }

        $array['title'] = "Ajout du nouveau modèle";
        $array['addModeleForm'] = $form->createView();

        return $this->render('admin/modele/addModele.html.twig', $array);;
    }

    /**
     * @Route("/modele/edit/{id}", name="modele_edit")
     */
    public function editAction(Modele $modele, Request $obj_request)
    {
        $em = $this->getDoctrine()->getManager();
        $obj_modele = $em->getRepository(Modele::class)->find($modele->getId());
        $form = $this->createForm(ModeleFormType::class, $obj_modele);
        $form->handleRequest($obj_request);

        if ($form->isSubmitted() && $form->isValid()) {

            $modele_exists = !empty($em->getRepository(Modele::class)->findBy(['nom' => $obj_modele->getNom(), 'marque' => $obj_modele->getMarque()])) ? true : false;
            
            if ($modele_exists) {               
                $this->addFlash('error', "Le modele ".$obj_modele->getMarque()->getNom()." ".$obj_modele->getNom()." est déjà enregistré.");
                return $this->redirectToRoute('modele_edit', ['id' => $obj_modele->getId()]);
            } else {

                $obj_marque = $em->getRepository(Marque::class)->find($modele->getMarque()->getId());
                $obj_modele->setMarque($obj_marque);

                $em->persist($obj_modele);
                $em->flush();

                $this->addFlash('success', "Vos modifications ont bien été enregistrées.");
                return $this->redirectToRoute('modeles');
            }
        }

        $array['title'] = "Modification du modèle ".$obj_modele->getMarque()->getNom()." ".$obj_modele->getNom();
        $array['editModeleForm'] = $form->createView();

        return $this->render('admin/modele/editModele.html.twig', $array);
    }
}
