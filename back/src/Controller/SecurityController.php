<?php
namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Likes;
use App\Entity\User;
use App\Repository\LikesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityController extends ApiController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Request $request
     * @param PasswordHasherInterface $encoder
     * @return JsonResponse
     */
	#[Route(path: '/api/register', name: 'register-check', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $encoder): JsonResponse
    {
        $request = $this->transformJsonBody($request);
        $username = $request->get('username');
        $password = $request->get('password');

        if (empty($username) || empty($password)) {
            return $this->respondValidationError("Invalid Username or Password");
        }


        $user = new User();
        $user->setPassword($encoder->hashPassword($user, $password));
        $user->setUsername($username);
        $this->em->persist($user);
        $this->em-> flush();
        return $this->respondWithSuccess(sprintf('User %s successfully created', $user->getUsername()));
    }

    /**
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
	#[Route(path: '/api/login_check', name: 'login-check', methods: ['POST'])]
    public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }

    private function getUserIDByRequest(ManagerRegistry $doctrine, Request $request): User
    {
        $token = $request->headers->get('Authorization');
        
        $tokenParts = explode(".", $token);  
        //$tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        
        $user = $doctrine->getManager()->getRepository(User::class)->findOneBySomeField($jwtPayload->username);
        return $user;
    }

    /**
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(path: '/api/me', name: 'api-me', methods: ['POST'])]
    public function getMe(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $user = $this->getUserIDByRequest($doctrine, $request);

        return new JsonResponse(['id' => $user->getId(), "roles" => $user->getRoles()]);
    }

    #[Route(path: '/api/likes', name: 'api-like', methods: ['POST'])]
    public function getMyLike(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $user = $this->getUserIDByRequest($doctrine, $request);

        $likes = $doctrine->getManager()->getRepository(Likes::class)->findByExampleField($user);

        return new JsonResponse(["user" => $user, $likes]);
    }

    /**
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(path: '/api/articles/search', name: 'api-articles-search', methods: ['POST'])]
    public function getArticlesFromNameOrContent(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $request = $this->transformJsonBody($request);
        $searchText = $request->get('searchText');

        if (empty($searchText)) {
            return $this->respondValidationError("Invalid searchText");
        }
        
        $articles = $doctrine->getManager()->getRepository(Articles::class)->findBySearchValue($searchText);
        $returnArticles = array();
        $i = 0;
        foreach ($articles as $article) {
            $tempArticle = array(
                "id" => $article->getId()
            );

            array_push($returnArticles, $i, $tempArticle);
            $i++;
        }

        return new JsonResponse($returnArticles);
    }
}