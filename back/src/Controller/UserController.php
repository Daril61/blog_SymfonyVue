<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\HttpFoundation\Session\Session;

#[Route('/utilisateur')]
class UserController extends AbstractController
{

    #[Route('/', name: 'utilisateur_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Session $session): Response
    {
		//besoin de droits admin
		$utilisateur = $this->getUser();
		if(!$utilisateur)
		{
			$session->set("message", "Merci de vous connecter");
			return $this->redirectToRoute('app_login');
		}

		else if(in_array('ROLE_ADMIN', $utilisateur->getRoles())){
			return $this->render('utilisateur/index.html.twig', [
				'utilisateurs' => $userRepository->findAll(),
			]);
		}

		return $this->redirectToRoute('home');
    }

    #[Route('/register', name: 'utilisateur_new', methods: ['GET','POST'])]
    public function new(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordEncoder, Session $session): Response
    {
        //test de sécurité, un utilisateur connecté ne peut pas s'inscrire
		$utilisateur = $this->getUser();
		if($utilisateur)
		{
			$session->set("message", "Vous ne pouvez pas créer un compte lorsque vous êtes connecté");
			return $this->redirectToRoute('membre');
		}

		$utilisateur = new User();
		$form = $this->createForm(UserType::class, $utilisateur);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager = $doctrine->getManager();
			$utilisateur->setPassword($passwordEncoder->hashPassword($utilisateur, $utilisateur->getPassword()));
			//uniquement pour créer un admin
			
			/*$role = ['ROLE_ADMIN'];
			$utilisateur->setRoles($role);*/
			
			$entityManager->persist($utilisateur);
			$entityManager->flush();

			return $this->redirectToRoute('utilisateur_index');
		}

		return $this->render('utilisateur/new.html.twig', [
		'utilisateur' => $utilisateur,
		'form' => $form->createView(),
		]);
    }
}
