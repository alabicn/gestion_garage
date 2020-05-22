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
use App\Entity\Option;
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

            $prixParam = $obj_request->request->get('activate_prix') !== null && $obj_request->request->get('activate_prix') == "on" ? intval($obj_request->request->get('recherche_voiture_form')['prix']) : null;
            
            $donnees = $form->getData();
            $donnees['prix'] = $prixParam;

            $arr_voitures = $this->rechercheVoitures($donnees);
            $array['arrVoitures'] = json_encode($arr_voitures);
        }


        $array['title'] = 'Recherche avancée';
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

        // modèle
        /*$modele = $donnees['modele'];
        $modele_part1 = "";
        $modele_part2 = "";
        $entity_modele = [];
        if ($modele != "") {
            $arr_modele = explode(' ', $modele);
            $modele_part1 = $arr_modele[0];
            $entity_modele = $em->getRepository(Modele::class)->findBy(['nom' => $modele_part1]);
            if (count($arr_modele) > 1) {
                $modele_part2 = $arr_modele[1];
                $obj_marque = $em->getRepository(Marque::class)->findOneBy(['nom' => $modele_part1]);
                $entity_modele = $em->getRepository(Modele::class)->findBy(['marque' => $obj_marque, 'nom' => $modele_part2]);
            }
        }*/
 

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

        // boite de vitesse
        $arr_boites = $donnees['boites'];

        // nombre de portes
        $arr_nbPortes = $donnees['nbPortes'];

        // options
        /*$arr_options = $donnees['options'];
        $entity_options = [];
        if (count($arr_options) > 0) {
            foreach ($arr_options as $obj_option) {
                $entity_options[] = $obj_option;
            }
        } else {
            $entity_options[] = $em->getRepository(Option::class)->findAll();
        }*/

        // prix
        $prix = $donnees['prix'] !== null ? $donnees['prix'] / 1.2 : $donnees['prix']; // si le prix est parametre de recherche on divise TVA car en BDD les prix sont HT

        $arr_criteres = [
            'marques' => $entity_marques,
          //'modele' => $entity_modele,
            'garages' => $entity_garages,
            'typesCarroserie' => $arr_typeCarrosserie,
            'boites' => $arr_boites,
            'carburants' => $arr_carburant,
            'nbPortes' => $arr_nbPortes,
            //'options' => $entity_options,
            'prix' => $prix,
            'a_vendre' => true,
        ];
      
        $voitures = $em->getRepository(Voiture::class)->findVoitureByCriteres($arr_criteres);
        $arr_voitures = [];

        // Formatage des données
        foreach ($voitures as $voiture) {

            $options = [];
            foreach ($voiture->getVoitureOptions() as $voitureOption) {
                $options[] = $voitureOption->getNombre() > 1 ? $voitureOption->getOption()->getTitle()." (".$voitureOption->getNombre().") " : $voitureOption->getOption()->getTitle();
                asort($options);
            }
        
            $arr_voitures[] = [
                'modele' => $voiture->getModele()->getMarque()->getNom()." ".$voiture->getModele()->getNom(),
                'garage' => $voiture->getGarage()->getNom(),
                'annee' => !is_null($voiture->getDateFabrication()) ? $voiture->getDateFabrication()->format('Y') : "/",
                'kilometrage' => !is_null($voiture->getKilometrage()) ? number_format($voiture->getKilometrage(), 0, '', ' ')." km" : "/",
                'carrosserie' => !is_null($voiture->getTypeCarrosserie()) ? $voiture->getTypeCarrosserie() : "/",
                'carburant' => !is_null($voiture->getCarburant()) ? $voiture->getCarburant() : "/",
                'boite' => !is_null($voiture->getBoiteDeVitesse()) ? $voiture->getBoiteDeVitesse() : "/",
                'portes' => !is_null($voiture->getNbPortes()) ? $voiture->getNbPortes() : "/",
                'options' => count($options) > 0 ? implode("<br>", $options) : "/",
                'prix' => !is_null($voiture->getPrix()) ? $this->serviceInformations->format_price($voiture->getPrix()) : "/"
            ];
        }

        return $arr_voitures;
    }
}
