<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Option;
use App\Form\OptionFormType;

/** @Route("/admin") */
class OptionController extends AbstractController
{
    /**
     * @Route("/options", name="options")
     */
    public function indexAction(EntityManagerInterface $em)
    {            
        $arr_options = $em->getRepository(Option::class)->findAll();

        uasort($arr_options, function($a, $b) {
            return strnatcmp($a->getTitle(), $b->getTitle());
        });

        $array['options'] = $arr_options;
        $array['title'] = 'Liste des options';
    
        return $this->render('admin/option/options.html.twig', $array);
    }

    /**
     * @Route("/option/add", name="option_add")
     */
    public function addAction(Request $obj_request)
    {
        $obj_option = new Option();
        $form = $this->createForm(OptionFormType::class, $obj_option);
        $form->handleRequest($obj_request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on cherche le manager
            $em = $this->getDoctrine()->getManager();
            
            $option_exists = !empty($em->getRepository(Option::class)->findBy(['title' => $obj_option->getTitle()])) ? true : false;

            if ($option_exists) {               
                $this->addFlash('error', "L'option' ".$obj_option->getTitle()." est déjà enregistrée.");
                return $this->redirectToRoute('option_add');
            } else {
                $em->persist($obj_option);
                $em->flush();

                $this->addFlash('success', "Vous avez ajouté la nouvelle option ".$obj_option->getTitle());
                return $this->redirectToRoute('options');
            }
        }

        $array['title'] = "Ajout de la nouvelle option";
        $array['addOptionForm'] = $form->createView();

        return $this->render('admin/option/addOption.html.twig', $array);;
    }

    /**
     * @Route("/option/edit/{id}", name="option_edit")
     */
    public function editAction(Option $option, Request $obj_request)
    {
        $em = $this->getDoctrine()->getManager();
        $obj_option = $em->getRepository(Option::class)->find($option->getId());
        $form = $this->createForm(OptionFormType::class, $obj_option);
        $form->handleRequest($obj_request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($obj_option);
            $em->flush();

            $this->addFlash('success', "Vos modifications ont bien été enregistrées");
            return $this->redirectToRoute('options');
        }

        $array['title'] = "Modification de l'option ".$obj_option->getTitle();
        $array['editOptionForm'] = $form->createView();

        return $this->render('admin/option/editOption.html.twig', $array);
    }
}