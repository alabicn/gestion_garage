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
class MarqueController extends AbstractController
{
     /**
     * @Route("/marques", name="marques")
     */
    public function indexAction(EntityManagerInterface $em, Security $security)
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
            
            $em->persist($obj_marque);
            $em->flush();

            $this->addFlash('success', "Vous avez ajouté la nouvelle marque ".$obj_marque->getNom()." dans votre garage");
            return $this->redirectToRoute('marques');
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

            $em->flush();

            $this->addFlash('success', "Vos modifications ont bien été enregistrées");
            return $this->redirectToRoute('marques');
        }

        $array['title'] = "Modification de la marque".$obj_marque->getNom();
        $array['editMarqueForm'] = $form->createView();

        return $this->render('admin/marque/editMarque.html.twig', $array);
    }
}
