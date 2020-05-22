<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\Marque;
use App\Form\MarqueFormType;

/** @Route("/admin") */
class MarqueController extends AbstractController
{
     /**
     * @Route("/marques", name="marques")
     */
    public function indexAction(EntityManagerInterface $em)
    {            
        $arr_marques = $em->getRepository(Marque::class)->findAll();

        uasort($arr_marques, function($a, $b) {
            return strnatcmp($a->getNom(), $b->getNom());
        });

        $array['marques'] = $arr_marques;
        $array['title'] = 'Liste des marques';
    
        return $this->render('admin/marque/marques.html.twig', $array);
    }

    /**
     * @Route("/marque/add", name="marque_add")
     */
    public function addAction(Request $obj_request)
    {
        $obj_marque = new Marque();
        $form = $this->createForm(MarqueFormType::class, $obj_marque);
        $form->handleRequest($obj_request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on cherche le manager
            $em = $this->getDoctrine()->getManager();
            $marque_exists = !empty($em->getRepository(Marque::class)->findBy(['nom' => $obj_marque->getNom()])) ? true : false;

            if ($marque_exists) {               
                $this->addFlash('error', "La marque ".$obj_marque->getNom()." est déjà enregistrée.");
                return $this->redirectToRoute('marque_add');
            } else {
                $em->persist($obj_marque);
                $em->flush();
                $this->addFlash('success', "Vous avez ajouté la nouvelle marque ".$obj_marque->getNom()." dans votre garage.");
                return $this->redirectToRoute('marques');
            }    
        }

        $array['title'] = "Ajout de la nouvelle marque";
        $array['addMarqueForm'] = $form->createView();

        return $this->render('admin/marque/addMarque.html.twig', $array);;
    }

    /**
     * @Route("/marque/edit/{id}", name="marque_edit")
     */
    public function editAction(Marque $marque, Request $obj_request)
    {
        $em = $this->getDoctrine()->getManager();
        $obj_marque = $em->getRepository(Marque::class)->find($marque->getId());
        $form = $this->createForm(MarqueFormType::class, $obj_marque);
        $form->handleRequest($obj_request);

        if ($form->isSubmitted() && $form->isValid()) {

            $marque_exists = !empty($em->getRepository(Marque::class)->findBy(['nom' => $obj_marque->getNom()])) ? true : false;
            
            if ($marque_exists) {               
                $this->addFlash('error', "La marque ".$obj_marque->getNom()." est déjà enregistrée.");
                return $this->redirectToRoute('marque_edit', ['id' => $obj_marque->getId()]);
            } else {
                $em->persist($obj_marque);
                $em->flush();
                $this->addFlash('success', "Vos modifications ont bien été enregistrées.");
                return $this->redirectToRoute('marques');
            }
        }

        $array['title'] = "Modification de la marque ".$obj_marque->getNom();
        $array['editMarqueForm'] = $form->createView();

        return $this->render('admin/marque/editMarque.html.twig', $array);
    }
}