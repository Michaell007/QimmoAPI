<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurController extends AbstractController
{

    private $userRepo;
    private $passwordHasher;
    private $security;
    public function __construct(
        UserPasswordHasherInterface $passwordHasher, 
        UtilisateurRepository $userRepo,
        Security $security) {
        $this->passwordHasher = $passwordHasher;
        $this->userRepo = $userRepo;
        $this->security = $security;
    }

    #[Route('user/create', name: 'app_utilisateur', methods: ["POST"])]
    public function indexUser(Request $request): JsonResponse
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
        $user->setContact($request_data["contact"]);
        $user->setRoles( array('ROLE_PUBLISHER') );

        // save user
        $this->userRepo->save($user, true);

        return $this->json([
                'code' => 200,
                'message' => 'Operation effectuee avec succes'
            ], 200, []
        );

        
    }

    #[Route('api/user/edit', name: 'app_utilisateur_edit', methods: ["PUT"])]
    public function editUser(Request $request): JsonResponse
    {

        // get user current
        $userCurrent = $this->security->getUser();

        // get data request
        $request_data = json_decode($request->getContent(), true);

        // save user and check if username exist
        $verifUsername = $this->userRepo->findOneByUsername($request_data["email"]);
        if ($verifUsername != null && $verifUsername->getEmail() != $userCurrent->getEmail()  ) {
            return $this->json([
                'code' => 401,
                'message' => "Ce email d'utilisateur est déjà utilisé."
            ]);
        }

        $userCurrent->setUsername($request_data["email"]);
        $userCurrent->setNom($request_data["nom"]);
        $userCurrent->setPrenom($request_data["prenom"]);
        $userCurrent->setEmail($request_data["email"]);
        // save userCurrent
        $this->userRepo->save($userCurrent, true);

        return $this->json([
                'code' => 200,
                'data' => $userCurrent
            ], 200, [], ['groups' => 'show_user']
        );

        
    }

}
