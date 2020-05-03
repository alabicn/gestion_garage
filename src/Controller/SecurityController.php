<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\Client;
use App\Form\RegistrationFormType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function registerAction(Request $obj_request, UserPasswordEncoderInterface $encoder)
    {
        $obj_client = new Client();
        $form = $this->createForm(RegistrationFormType::class, $obj_client);
        $form->handleRequest($obj_request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on cherche le manager
            $manager = $this->getDoctrine()->getManager();
            // encoder le mot de passe
            $hashMDP = $encoder->encodePassword($obj_client, ($form->get('plainPassword')->getData()));
            // setMDP
            $obj_client->setPassword($hashMDP)
                       ->setRoles(['ROLE_USER']);

            $manager->persist($obj_client);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        $array['registrationForm'] = $form->createView();
        $array['title'] = 'Formulaire d\'inscription';

        return $this->render('security/register.html.twig', $array);
    }
}
