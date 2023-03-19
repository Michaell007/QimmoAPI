<?php

namespace App\Controller;

use App\Entity\Souscripteur;
use App\Repository\SouscripteurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SouscripteurController extends AbstractController
{

    private $souscripteurRepo;
    public function __construct(SouscripteurRepository $souscripteurRepo)
    {
        $this->souscripteurRepo = $souscripteurRepo;
    }

    #[Route('/souscripteur/create', name: 'app_souscripteur', methods: ["POST"])]
    public function indexSouscripteur(Request $request): JsonResponse
    {

        // get data request
        $request_data = json_decode($request->getContent(), true);

        $verifUsername = $this->souscripteurRepo->findOneByEmail($request_data["email"]);
        if ($verifUsername != null) {
            return $this->json([
                'code' => 401,
                'message' => "Ce username d'utilisateur est déjà utilisé."
            ]);
        }

        // save souscripteur
        $souscripteur = new Souscripteur();
        $souscripteur->setNom($request_data["nom"]);
        $souscripteur->setPrenom($request_data["prenom"]);
        $souscripteur->setEmail($request_data["email"]);
        $souscripteur->setType($request_data["type"]);
        $souscripteur->setPrice($request_data["price"]);
        $souscripteur->setSurface($request_data["surface"]);
        $souscripteur->setChambre($request_data["chambre"]);
        $souscripteur->setDouche($request_data["douche"]);
        $this->souscripteurRepo->save($souscripteur, true);

        return $this->json(
            [
                'code' => 200,
                'message' => 'Operation effectuee avec succes'
            ], 200, []
        );
    }
    
}
