<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Voiture;

use Knp\Component\Pager\PaginatorInterface;

/** @Route("/admin") */
class VoitureController extends AbstractController
{
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
        

        return $this->render('admin/voiture/addVoiture.html.twig', $array);
    }
}
