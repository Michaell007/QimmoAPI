<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Annonce;
use App\Entity\TypeAnnonce;
use App\Repository\ImageRepository;
use App\Repository\AnnonceRepository;
use App\Repository\TypeAnnonceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnonceController extends AbstractController
{

    private $repoAnnonce;
    private $repoType;
    private $slugger;
    private $repoImg;
    private $security;
    public function __construct(
        AnnonceRepository $repoAnnonce, 
        SluggerInterface $slugger, 
        TypeAnnonceRepository $repoType, 
        ImageRepository $repoImg,
        Security $security) {
        $this->repoAnnonce = $repoAnnonce;
        $this->slugger = $slugger;
        $this->repoType = $repoType;
        $this->repoImg = $repoImg;
        $this->security = $security;
    }

    /**
     * Retourne les 12 dernieres Annonces
     *
     * @return JsonResponse
     */
    #[Route('/annonce/latest', name: 'app_annonce', methods: ['GET'])]
    public function index(): JsonResponse {

        $annonces = $this->repoAnnonce->findBy([],  ['createdAt' => 'DESC'], 12);
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

    /**
     * Retourne one Annonce
     *
     * @return JsonResponse
     */
    #[Route('/annonce/{id}', name: 'app_annonce', methods: ['GET'])]
    public function getAnnonceById($id): JsonResponse {

        $annonce = $this->repoAnnonce->findOneById($id);
        return $this->json([
                'code' => 200,
                'data' => $annonce
            ], 200, [], ['groups' => 'show_annonce']
        );
    }

    /**
     * Add Annones
     * @return JsonResponse
     */
    #[Route('/annonce/create', name: 'app_add_annonce', methods: ['POST'])]
    public function addAnnonce(Request $request): JsonResponse {
        // header("Access-Control-Allow-Origin: *");

        // get data request
        $request_data = json_decode($request->getContent(), true);

        // get user current
        $userCurrent = $this->security->getUser();

        // dd( json_decode($request->getContent(), true) );
        // $extension = pathinfo($request_data["image"]["filename"], PATHINFO_EXTENSION);
        // $originalFilename = pathinfo($request_data["image"]["filename"], PATHINFO_FILENAME);
        // $safeFilename = $this->slugger->slug($originalFilename);
        // $newFilename = $safeFilename.'-'.uniqid().'.'.$extension;
        // file_put_contents($request_data["image"]['filename'], base64_decode($request_data["image"]['value']));

        // create annonce
        $annonce = new Annonce();
        $annonce->setEtiquette('');
        $annonce->setLinkUrl("");
        $annonce->setTitle($request_data["titre"]);
        $annonce->setDescription($request_data["description"]);
        $annonce->setDimension($request_data["dimension"]);
        $annonce->setMontant($request_data["montant"]);
        $annonce->setLieu($request_data["lieu"]);
        $annonce->setNbLit($request_data["nbLit"]);
        $annonce->setNbDouche($request_data["nbDouche"]);
        $annonce->setIsForOwnerSite(true);
        $annonce->setAnnonceur($userCurrent);
        
        // add typeAnnonce
        $typeAnnonce = $this->repoType->findOneByLibelle($request_data["type"]);
        $annonce->setTypeAnnonce($typeAnnonce);
        // add image
        $image = new Image();
        $image->setUrl("property3.jpg");
        $this->repoImg->save($image, true);
        $annonce->addImage($image);

        // save annone
        $this->repoAnnonce->save($annonce, true);

        return $this->json(
            [
                'code' => 200,
                'message' => 'Annonce ajoutee avec succes'
            ], 200, []
        );
    }



}
