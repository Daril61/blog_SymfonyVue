<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Favorite;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

#[Route("/article")]
class ArticleController extends AbstractController
{
    #[Route("/", name: "article_index")]
    public function article_index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route('/new', name: 'article_new')]
    public function new(ManagerRegistry $doctrine, Request $request, Session $session): Response
    {
        // Vérification que l'utilisateur est bien admin
        $utilisateur = $this->getUser();
        // Si l'utilisateur n'est pas connecté
        if(!$utilisateur)
        {
            $session->set("message", "Merci de vous connecter");
            return $this->redirectToRoute('app_login');
        }
        
        // Vérification que l'utilisateur est bien admin
        if(!$this->isGranted('ROLE_ADMIN')){
            $session->set("message", "Vous devez être administrateur pour pouvoir utiliser cette catégorie");
            return $this->redirectToRoute('membre');
        }

        $article = new Articles();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            
            // Récupération de la date de maintenant
            $date = new \DateTime('@'.strtotime('now'));
            $article->setCreationDate($date);
            $article->setUser($utilisateur);

            // Sauvegarde du nouvelle article dans la base de données
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            ]);
    }

    /*#[Route('/{id}/edit', name: 'article_edit')]
    public function edit(ManagerRegistry $doctrine, Request $request, Articles $article, Session $session, $id): Response
    {
        $utilisateur = $this->getUser();

        // Si l'utilisateur n'est pas connecté
        if(!$utilisateur)
        {
            $session->set("message", "Merci de vous connecter");
            return $this->redirectToRoute('app_login');
        }
        
        // Vérification que l'utilisateur est bien admin
        if(!$this->isGranted('ROLE_ADMIN')){
            $session->set("message", "Vous devez être administrateur pour pouvoir utiliser cette catégorie");
            return $this->redirectToRoute('membre');
        }

        $updated

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword()));
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('utilisateur_index');
        }

        return $this->render('utilisateur/edit.html.twig', [
        'utilisateur' => $utilisateur,
        'form' => $form->createView(),
        ]);
    }*/

    #[Route("/favorite/{id}", name: "article_favorite", methods: ["POST"])]
    public function like(ManagerRegistry $doctrine, Request $request, Session $session, $id) {
        $utilisateur = $this->getUser();

        // Si l'utilisateur n'est pas connecté
        if(!$utilisateur)
        {
            $session->set("message", "Merci de vous connecter");
            return $this->redirectToRoute('app_login');
        }

        // Récupération de l'article avec l'id passé en paramètre
        $article = $doctrine->getRepository(Articles::class)->find($id);

        // On regarde si l'utilisateur mis en favori l'article
        $exist = $doctrine->getRepository(Favorite::class)->findOneBy([
            'article' => $article,
            'user' => $utilisateur,
        ]);

        $entityManager = $doctrine->getManager();

        // Si l'utilisateur à déjà favori l'article alors on retire le favori
        if($exist) {
            $entityManager->remove($exist);
            $entityManager->flush();
        
        // Sinon alors on favori l'article
        } else {
            $fav = new Favorite();
            $fav->setArticle($article);
            $fav->setUser($utilisateur);

            $entityManager->persist($fav);
            $entityManager->flush();
        }

        return new Response();
    }
}
