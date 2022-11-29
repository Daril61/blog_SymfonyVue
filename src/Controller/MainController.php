<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route("/app/{slug?}", name: "app", requirements: ['slug' => '.+'])]
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route("/api/helloword/{name}", name: "api_helloword")]
    public function apiHelloword(string $name): Response
    {
      return new JsonResponse('hello ' . $name);
    }
}
