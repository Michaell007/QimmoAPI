<?php

namespace App\Controller;

use App\Entity\Critere;
use App\Entity\Utilisateur;
use App\Repository\CritereRepository;
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
        UtilisateurRepository $userRepo,
        CritereRepository $repoCritere) {
        $this->passwordHasher = $passwordHasher;
        $this->userRepo = $userRepo;
        $this->repoCritere = $repoCritere;
    }

    #[Route('user/create', name: 'app_utilisateur', methods: ["POST"])]
    public function index(Request $request): JsonResponse
    {

        // get data request
        $request_data = json_decode($request->getContent(), true);

        // save user and check if username exist
        $souscripteur = new Utilisateur();
        $verifUsername = $this->userRepo->findOneByUsername($request_data["email"]);
        if ($verifUsername != null) {
            return $this->json([
                'code' => 401,
                'message' => "Ce username d'utilisateur est déjà utilisé."
            ]);
        }

        // hash the password (based on the security.yaml config for the $souscripteur class)
        $hashedPassword = $this->passwordHasher->hashPassword($souscripteur, $request_data["password"]);
        $souscripteur->setPassword($hashedPassword);
        $souscripteur->setUsername($request_data["email"]);
        $souscripteur->setNom($request_data["nom"]);
        $souscripteur->setPrenom($request_data["prenom"]);
        $souscripteur->setEmail($request_data["email"]);

        // save critere
        // $critere = new Critere();
        // $critere->setType($request_data["type"]);
        // $critere->setPrice($request_data["price"]);
        // $critere->setSurface($request_data["surface"]);
        // $critere->setChambre($request_data["chambre"]);
        // $critere->setDouche($request_data["douche"]);
        // $this->repoCritere->save($critere, true);

        // save souscripteur
        $this->userRepo->save($souscripteur, true);

        return $this->json([
                'code' => 200,
                'message' => 'Operation effectuee avec succes'
            ], 200, []
        );

        
    }
}
