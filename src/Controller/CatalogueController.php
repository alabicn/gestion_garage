<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ServiceInformations;

use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Voiture;
use App\Entity\Marque;
use App\Entity\Modele;
use App\Entity\Garage; 
use App\Form\RechercheVoitureFormType;

use Knp\Component\Pager\PaginatorInterface;

class CatalogueController extends AbstractController
{
    private $serviceInformations;

    public function __construct(ServiceInformations $serviceInformations)
    {
        $this->serviceInformations = $serviceInformations;
    }

    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $obj_request)
    {
        $queryBuilder = $em->getRepository(Voiture::class)->getWithSearchQueryBuilder();

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $obj_request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );

        //$arr_voitures = $em->getRepository(Voiture::class)->findAll();

        //$array['voitures'] = array_slice($arr_voitures, 40);
        $array['title'] = 'Catalogue des voitures';
        $array['pagination'] = $pagination;

        return $this->render('catalogue/catalogue.html.twig', $array);
    }

    /**
     * @Route("/catalogue/voiture/{id}", name="product_detailed")
     */
    public function detailProduct(Voiture $voiture, Request $obj_request)
    {
        $em = $this->getDoctrine()->getManager();
        $obj_voiture = $em->getRepository(Voiture::class)->find($voiture->getId());

        $array['title'] = "Détails de ".$obj_voiture->getModele()->getMarque()->getNom()." ".$obj_voiture->getModele()->getNom();
        $array['voiture'] = $obj_voiture;

        return $this->render('catalogue/productDetail.html.twig', $array);
    }

    /**
     * @Route("/catalogue/advanced", name="catalogue_advanced")
     */
    public function rechercheAdvanced(Request $obj_request)
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

        // marques
        $arr_marques = $donnees['marque'];     
        $entity_marques = [];
        if (count($arr_marques) > 0) {
            foreach ($arr_marques as $obj_marque) {
                $entity_marques[] = $obj_marque;
            }
        } else {
            $entity_marques = $em->getRepository(Marque::class)->findAll();
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

        // carburant
        $arr_carburant = $donnees['carburant'];

        // nombre de portes
        $arr_nbPortes = $donnees['nbPortes'];

        $arr_criteres = [
            'marques' => $entity_marques,
            'garages' => $entity_garages,
            'typesCarroserie' => $arr_typeCarrosserie,
            'carburants' => $arr_carburant,
            'nbPortes' => $arr_nbPortes,
            'a_vendre' => true,
        ];
      
        $voitures = $em->getRepository(Voiture::class)->findVoitureByCriteres($arr_criteres);
        $arr_voitures = [];

        // Formatage des données
        foreach ($voitures as $voiture) {
        
            $arr_voitures[] = [
                'modele' => $voiture->getModele()->getMarque()->getNom()." ".$voiture->getModele()->getNom(),
                'garage' => $voiture->getGarage()->getNom(),
                'fabrication' => !is_null($voiture->getDateFabrication()) ? $voiture->getDateFabrication()->format('d/m/Y') : "/",
                'kilometrage' => !is_null($voiture->getKilometrage()) ? number_format($voiture->getKilometrage(), 0, '', ' ')." km" : "/",
                'carrosserie' => !is_null($voiture->getTypeCarrosserie()) ? $voiture->getTypeCarrosserie() : "/",
                'carburant' => !is_null($voiture->getCarburant()) ? $voiture->getCarburant() : "/",
                'portes' => !is_null($voiture->getNbPortes()) ? $voiture->getNbPortes() : "/",
                'prix' => !is_null($voiture->getPrix()) ? $this->serviceInformations->format_price($voiture->getPrix()) : "/"
            ];
        }

        return $arr_voitures;
    }
}
