<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
    public function indexAction(Request $obj_request, Security $security)
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
        $manager = $this->getDoctrine()->getManager();

        // Critères
        $arr_matieres = $manager->getRepository(Marque::class)->findAll();
        $array['elem'] = $arr_matieres;
        $array['addMarqueForm'] = $form->createView();
        $array['title'] = 'Ajout de la nouvelle marque';
        $array['editing_text'] = 'Edition d\'une marque';
        $array['the_edit_text'] = 'Utiliser ce bouton pour éditer la marque';
        $array['the_save_text'] = 'En cours d\'édition, utiliser ce bouton pour valider les modifications ou la nouvelle marque';
        $array['the_delete_text'] = 'En cours d\'édition utiliser ce bouton pour supprimer la marque';

        $array['action'] = 'marque';

        $array['droitEdit'] = $security->isGranted('ROLE_ADMIN') ? true : false;
        $array['droitCreate'] = $security->isGranted('ROLE_ADMIN') ? true : false;
        $array['droitDelete'] = $security->isGranted('ROLE_ADMIN') ? true : false;
        
        // Routes
        $array['saveRoute'] = 'marque_save';
        $array['deleteRoute'] = 'marque_delete';


        return $this->render('admin/marque/marques.html.twig', $array);
    }

    /**
     * @Route("/marque/save", name="marque_save")
     */
    public function saveAction()
    {

    }

    /**
     * @Route("/marque/delete", name="marque_delete")
     */
    public function deleteAction()
    {

    }
}
