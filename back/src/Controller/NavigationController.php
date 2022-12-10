<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\Session;

class NavigationController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(Session $session)
    {
            $return = [];

            if($session->has('message'))
            {
                    $message = $session->get('message');
                    $session->remove('message'); //on vide la variable message dans la session
                    $return['message'] = $message; //on ajoute à l'array de paramètres notre message
            }
            return $this->render('navigation/home.html.twig', $return);
    }

    #[Route('/membre', name: 'membre')]
    public function membre(Session $session)
    {
            $return = [];

            if($session->has('message'))
            {
                    $message = $session->get('message');
                    $session->remove('message'); //on vide la variable message dans la session
                    $return['message'] = $message; //on ajoute à l'array de paramètres notre message
            }
            return $this->render('navigation/membre.html.twig', $return);
    }

    #[Route('/admin', name: 'admin')]
    public function admin(Session $session)
    {
        $utilisateur = $this->getUser();
        // Si l'utilisateur n'est pas connecté
        if(!$utilisateur)
        {
            $session->set("message", "Merci de vous connecter");
            return $this->redirectToRoute('app_login');
        }
        
        // Vérification que l'utilisateur est bien admin
        if($this->isGranted('ROLE_ADMIN')){
            return $this->render('navigation/admin.html.twig');
        }

        return $this->redirectToRoute('home');
    }

}