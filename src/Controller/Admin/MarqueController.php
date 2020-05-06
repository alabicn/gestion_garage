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
    public function indexAction(Request $obj_request, EntityManagerInterface $em)
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
     * @Route("/marque/save", name="marque_save")
     */
    public function saveAction()
    {
        $obj_marque = new Marque();
        $form = $this->createForm(MarqueFormType::class, $obj_marque);
        $form->handleRequest($obj_request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on cherche le manager
            $manager = $this->getDoctrine()->getManager();
            
            $manager->persist($obj_marque);
            $manager->flush();

            $this->addFlash('success', "Vous avez ajouté la nouvelle marque ".$obj_marque->getNom()." dans votre garage");
            return $this->redirectToRoute('marques');
        } else {
            $this->addFlash('error', "Vous ne disposez pas des droits requis pour accéder à cette fonctionnalité.");
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/marque/delete", name="marque_delete")
     */
    public function deleteAction()
    {

    }
}
