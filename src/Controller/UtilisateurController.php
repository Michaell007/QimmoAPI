<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurController extends AbstractController
{

    private $userRepo;
    private $passwordHasher;
    private $repoCritere;
    public function __construct(
        UserPasswordHasherInterface $passwordHasher, 
        UtilisateurRepository $userRepo) {
        $this->passwordHasher = $passwordHasher;
        $this->userRepo = $userRepo;
    }

    #[Route('user/create', name: 'app_utilisateur', methods: ["POST"])]
    public function index(Request $request): JsonResponse
    {

        // $this->denyAccessUnlessGranted('ROLE_COMMENT_ADMIN');

        // get data request
        $request_data = json_decode($request->getContent(), true);

        // save user and check if username exist
        $user = new Utilisateur();
        $verifUsername = $this->userRepo->findOneByUsername($request_data["email"]);
        if ($verifUsername != null) {
            return $this->json([
                'code' => 401,
                'message' => "Ce username d'utilisateur est déjà utilisé."
            ]);
        }

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $this->passwordHasher->hashPassword($user, $request_data["password"]);
        $user->setPassword($hashedPassword);
        $user->setUsername($request_data["email"]);
        $user->setNom($request_data["nom"]);
        $user->setPrenom($request_data["prenom"]);
        $user->setEmail($request_data["email"]);
        $user->setRoles( array('ROLE_PUBLISHER') );

        // save user
        $this->userRepo->save($user, true);

        return $this->json([
                'code' => 200,
                'message' => 'Operation effectuee avec succes'
            ], 200, []
        );

        
    }
}
