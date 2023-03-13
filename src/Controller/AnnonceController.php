<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{

    private $repoAnnonce;
    public function __construct(AnnonceRepository $repoAnnonce) {
        $this->repoAnnonce = $repoAnnonce;
    }

    /**
     * Retourne les 12 dernieres Annonces
     *
     * @return JsonResponse
     */
    #[Route('/annonce/latest', name: 'app_annonce', methods: ['GET'])]
    public function index(): JsonResponse {

        $annonces = $this->repoAnnonce->findBy([],  ['createdAt' => 'ASC'], 12);
        return $this->json([
                'code' => 200,
                'data' => $annonces
            ], 200, [], ['groups' => 'show_annonce']
        );
    }

    /**
     * Recherche Annones
     *
     * @return JsonResponse
     */
    #[Route('/annonces/search/{page}', name: 'app_annonces_search', methods: ['POST'])]
    public function searchAnnonce(int $page, Request $request): JsonResponse {

        // get request
        $request_data = json_decode($request->getContent(), true);

        $limit = 9;
        // get annonces
        $paginator = $this->repoAnnonce->findBySearch($request_data, $page);

        # Count all paginator
        $totalAnnonces = $paginator->count();

        // You can also call the count methods
        $totalAnnonceReturned = $paginator->getIterator()->count();

        # ArrayIterator
        $iterator = $paginator->getIterator();
        $maxPages = ceil($paginator->count() / $limit);
        $annonces = $paginator->getIterator()->getArrayCopy();

        return $this->json([
                'code' => 200,
                'data' => $annonces,
                'maxPages' => $maxPages
            ], 200, [], ['groups' => 'show_annonce']
        );
    }

    /**
     * list all Annones
     *
     * @return JsonResponse
     */
    #[Route('/annonces/all/{page}', name: 'app_list_annonces', methods: ['GET'])]
    public function listAllAnnonces(int $page): JsonResponse {

        $limit = 9;
        // get annonces
        $paginator = $this->repoAnnonce->getAllAnnoncesPaginator($page);

        # Count all paginator
        $totalAnnonces = $paginator->count();

        // You can also call the count methods
        $totalAnnonceReturned = $paginator->getIterator()->count();

        # ArrayIterator
        $iterator = $paginator->getIterator();
        $maxPages = ceil($paginator->count() / $limit);
        $annonces = $paginator->getIterator()->getArrayCopy();

        return $this->json([
                'code' => 200,
                'data' => $annonces,
                'maxPages' => $maxPages
            ], 200, [], ['groups' => 'show_annonce']
        );
    }



}
