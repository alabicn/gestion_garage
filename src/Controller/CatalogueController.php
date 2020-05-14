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

        dump($array);
        return $this->render('catalogue/catalogue.html.twig', $array);
    }

    /**
     * @Route("/catalogue/advanced", name="catalogue_advanced")
     */
    public function rechercheAvance(Request $obj_request)
    {
        // marques
        $form = $this->createForm(RechercheVoitureFormType::class);
        $form->handleRequest($obj_request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on cherche le manager
            $em = $this->getDoctrine()->getManager();
            
            dump($form);
        }


        $array['title'] = 'Recherche detaillé';
        $array['rechercheVoiture'] = $form->createView();

        dump($array);
        return $this->render('catalogue/catalogueForm.html.twig', $array);
    }

    /**
     * @Route("/catalogue/recherche", name="catalogue_recherche")
     */
    public function listeVoituresRecherche(Request $obj_request)
    {
        $em = $this->getDoctrine()->getManager();
        $response = new JsonResponse(); // Initiation de la réponse

        // Sauvegarde recherche
        $obj_request->getSession()->set('rechercheVoiture', serialize($obj_request->request->all()));

        $arr_voitures = $this->rechercheVoiture($obj_request->request->all());

        return $response->setData($arr_voitures);

    }

    private function rechercheVoiture() {
        $em = $this->getDoctrine()->getManager();

        $arrParam['marque'] = 1;
        // On récupère les marques demandées
        $marques = $arrParam['marque'];
        $entity_marques = Array();
        if (is_array($marques)) {
            foreach ($marques as $marque) {
                if($classe == -1){  // Si toute les marques sont sélectionnées
                    $entity_marques = null;
                    break;
                }
                $entity_marques[] = $em->getRepository(Marque::class)->find($marque);
            }
        }

        $arrParam["modele"] = 11;
        $modeles = $arrParam["modele"];
        dump($modeles);
        dump($em->getRepository(Modele::class)->find(11));
        $entity_modeles = Array();
        if (is_array($modeles)) {
            foreach ($modeles as $modele) {
                $entity_modeles[] = $em->getRepository(Modele::class)->find($modele);
            }
        }
        ///$entity_modeles = null;

        // On récupère le nom et le prénom
        /*$np = $arrParam["np"];
        $np1 = "";
        $np2 = "";
        if ($np != "") {
            $arr_np = explode(' ', $np);
            $np1 = $arr_np[0];
            if (count($arr_np) > 1) {
                $np2 = $arr_np[1];
            }
        }

        // bouton radio
        $fa = $arrParam["fa"];
        $aa = $arrParam["aa"];
        $aan = $arrParam["aan"];
        $fa_non_acquit = $arrParam["fa_non_acquit"];
        // Formatage code barres
        $cb = substr($arrParam["cb"], 3, -1);

        // Sans attribution, sans affectation numérique et archivés
        $sa = $arrParam["sa"];
        $san = $arrParam["san"];
        $ar = $arrParam["ar"];
        if(isset($arrParam['sansClasse']))
        {
            $sansClasse = $arrParam['sansClasse'];
            $arr_criteres = Array("classes" => $entity_classes, "options" => $options, "np1" => $np1, "np2" => $np2, "cb" => $cb, "sa" => $sa, "san" => $san, "ar" => $ar, "sansClasse"=>$sansClasse, "etb" => $this->getUser()->getEtablissement(), "fa" => $fa, "aa" => $aa, "aan" => $aan,"fa_non_acquit"=>$fa_non_acquit);
        }
        else
            $arr_criteres = Array("classes" => $entity_classes, "options" => $options, "np1" => $np1, "np2" => $np2, "cb" => $cb, "sa" => $sa, "san" => $san, "ar" => $ar, "etb" => $this->getUser()->getEtablissement(), "fa" => $fa, "aa" => $aa, "aan" => $aan,"fa_non_acquit"=>$fa_non_acquit);

            $select = array('e.nom','e.prenom','e.id','c.nom as classe','COUNT(DISTINCT a.id) as nbAttrib');
        */
        $arr_criteres = Array("marques"=>$entity_marques, "modeles"=>$entity_modeles);
        $select = array('v.immatriculation', 'v.prix');
        // Récupération des élèves
        $garage = $em->getRepository(Garage::class);

        $voitures = $em->getRepository(Voiture::class)->findVoitureByCriteres($arr_criteres, $select);
        $arr_voitures = array();

        dump($voitures);
        // Formatage des données
        foreach ($voitures as $voiture) {
        
            $arr_voitures[] = array(
                'prix' => $voiture['prix'],
            );
        }
        dump($arr_voitures);
        return $arr_voitures;
    }
}
