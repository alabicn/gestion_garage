<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ServiceInformations;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Voiture;
use App\Form\VoitureFormType;

use Knp\Component\Pager\PaginatorInterface;

/** @Route("/admin") */
class VoitureController extends AbstractController
{
    private $serviceInformations;

    public function __construct(ServiceInformations $serviceInformations)
    {
        $this->serviceInformations = $serviceInformations;
    }

    /**
     * @Route("/voitures", name="voitures")
     */
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $obj_request)
    {
        $queryBuilder = $em->getRepository(Voiture::class)->getWithSearchQueryBuilder();

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $obj_request->query->getInt('page', 1)/*page number*/,
            20/*limit per page*/
        );

        $array['pagination'] = $pagination;
        $array['title'] = 'Liste des voitures';

        return $this->render('admin/voiture/voitures.html.twig', $array);
    }

    /**
     * @Route("/voiture/add", name="voiture_add")
     */
    public function addAction(Request $obj_request)
    {
        $obj_voiture = new Voiture();
        $form = $this->createForm(VoitureFormType::class, $obj_voiture);
        $form->handleRequest($obj_request);

        if ($form->isSubmitted() && $form->isValid()) {

            $immatriculation = $form['immatriculation']->getData();
            $bonFormatImm = $this->serviceInformations->verificationImmatriculation($immatriculation);

            if ($bonFormatImm) {
                $em = $this->getDoctrine()->getManager();  
                if (!$form['voitureOptions']->isEmpty()) {
                    $prix = $form['prix']->getData();
                    foreach($form['voitureOptions']->getData() as $voitureOption) {
                        $prixOption = $voitureOption->getOption()->getPrix() * $voitureOption->getNombre();
                        
                        $prix += $prixOption;
                    }
                    $obj_voiture->setPrix($prix);
                }
                
                $obj_voiture->setAVendre(false);              

                $em->persist($obj_voiture);
                $em->flush();
                $this->addFlash('success', "La nouvelle voiture est ajoutée.");
                return $this->redirectToRoute('voitures');
            } else {
                $this->addFlash('error', "Le numéro d'immatriculation n'est pas au bon format.");
                return $this->redirectToRoute('voiture_add');
            }
        }

        $array['title'] = "Ajout de la nouvelle voiture";
        $array['addVoitureForm'] = $form->createView();

        return $this->render('admin/voiture/addVoiture.html.twig', $array);
    }
}
