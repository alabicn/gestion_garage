<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Voiture;
use App\Entity\Marque;
use App\Entity\Modele;
use App\Entity\Garage; 
use App\Form\RechercheVoitureFormType;

use Knp\Component\Pager\PaginatorInterface;

class CatalogueController extends AbstractController
{
    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $obj_request)
    {
        $queryBuilder = $em->getRepository(Voiture::class)->getWithSearchQueryBuilder();

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $obj_request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        //$arr_voitures = $em->getRepository(Voiture::class)->findAll();

        //$array['voitures'] = array_slice($arr_voitures, 40);
        $array['title'] = 'Catalogue des voitures';
        $array['pagination'] = $pagination;

        return $this->render('catalogue/catalogue.html.twig', $array);
    }

    /**
     * @Route("/catalogue/advanced", name="catalogue_advanced")
     */
    public function rechercheAvance(Request $obj_request)
    {
        $form = $this->createForm(RechercheVoitureFormType::class);
        $form->handleRequest($obj_request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $arr_voitures = $this->rechercheVoitures($form->getData());
            $array['arrVoitures'] = json_encode($arr_voitures);
        }


        $array['title'] = 'Recherche detaillé';
        $array['rechercheVoiture'] = $form->createView();

        return $this->render('catalogue/catalogueForm.html.twig', $array);
    }

    private function rechercheVoitures($donnees) 
    {
        $em = $this->getDoctrine()->getManager();

        // modèles
        $arr_modeles = $donnees['modele'];     
        $entity_modeles = [];
        foreach ($arr_modeles as $obj_modele) {
            $entity_modeles[] = $obj_modele;
        }

        // garages
        $arr_garages = $donnees['garage'];
        $entity_garages = [];
        if (count($arr_garages) > 0) {
            foreach ($arr_garages as $obj_garage) {
                $entity_garages[] = $obj_garage;
            }
        } else {
            $entity_garages = $em->getRepository(Garage::class)->findAllGaragesByStatus(false); // garages ouverte
        }

        // type de carrosserie
        $arr_typeCarrosserie = $donnees['typeCarrosserie'];

        // nombre de portes
        $arr_nbPortes = $donnees['nbPortes'];

        $arr_criteres = [
            'modeles' => $entity_modeles,
            'garages' => $entity_garages,
            'typesCarroserie' => $arr_typeCarrosserie,
            'nbPortes' => $arr_nbPortes,
            'a_vendre' => true,
        ];
      
        $voitures = $em->getRepository(Voiture::class)->findVoitureByCriteres($arr_criteres);
        $arr_voitures = [];

        // Formatage des données
        foreach ($voitures as $voiture) {
        
            $arr_voitures[] = [
                'garage' => $voiture->getGarage()->getNom(),
                'modele' => $voiture->getModele()->getMarque()->getNom()." ".$voiture->getModele()->getNom(),
                'immatriculation' => !is_null($voiture->getImmatriculation()) ? $voiture->getImmatriculation() : "/",
                'date_fabrication' => !is_null($voiture->getDateFabrication()) ? $voiture->getDateFabrication() : "/",
                'kilometrage' => !is_null($voiture->getKilometrage()) ? number_format($voiture->getKilometrage(), 0, '', ' ')." km" : "/",
                'typeCarrosserie' => !is_null($voiture->getTypeCarrosserie()) ? $voiture->getTypeCarrosserie() : "/",
                'nbPortes' => !is_null($voiture->getNbPortes()) ? $voiture->getNbPortes() : "/",
                'prix' => !is_null($voiture->getPrix()) ? $voiture->getPrix() : "/"
            ];
        }

        return $arr_voitures;
    }
}
