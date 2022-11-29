<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'user_registration')]
    public function register(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordEncoder)
    {
        // Création d'un utilisateur et d'un formulaire
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Encryptage du mot de passe et application sur l'utilisateur crée précédemment
            $password = $passwordEncoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            // Sauvegarde du nouvelle utilisateur
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('replace_with_some_route');
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }
}
