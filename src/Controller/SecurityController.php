<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;

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

            return $this->redirectToRoute('app_login');
        }

        $array['registrationForm'] = $form->createView();
        $array['title'] = 'Formulaire d\'inscription';

        return $this->render('security/register.html.twig', $array);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
