<?php

namespace App\Controller;

use Goutte\Client;
use App\Entity\Image;
use App\Entity\Annonce;
use App\Entity\TypeAnnonce;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeAnnonceRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ScraperController extends AbstractController
{

    private $em;
    private $repoType;
    public function __construct(EntityManagerInterface $em, TypeAnnonceRepository $repoType)
    {
        $this->em = $em;
        $this->repoType = $repoType;
    }

    #[Route('/scraper', name: 'app_scraper')]
    public function index(): JsonResponse
    {

        $client = new Client();

        /**
         * Scraping du site https://www.monimmo24.com
         */
        // $crawler = $client->request('GET', 'https://www.monimmo24.com/?status=a-louer&llocs=&lcats%5B%5D=&search_term=');
        // $crawler->filter('.geodir-category-listing.fl-wrap')->each(function ($node, $i) {

        //     // Scraping get element
        //     $title = $node->children()->eq(0)->children()->filter('a')->last()->attr('data-ltitle');
        //     $etiquette = $node->children()->eq(0)->children()->filter('a')->last()->attr('data-lprice');
        //     $etiquette = str_replace(' ', '', $etiquette);
        //     $montant = filter_var($etiquette, FILTER_SANITIZE_NUMBER_INT);
        //     $lieu = trim( $node->children()->eq(0)->children()->eq(1)->children()->filter('span')->text() );
        //     $linkOfAnnonce = $node->children()->eq(0)->filter('a')->eq(0)->attr("href");
        //     $img = $node->children()->eq(0)->filter('a')->eq(0)->children()->filter('img')->attr('src');

        //     // set Annonce
        //     $annonce = new Annonce();
        //     $node->children()->eq(1)->children()->filter('div.geodir-category-content-details > ul > li')->each(function($node) use ($annonce) {

        //         if ($node->filter('i')->attr('class') == 'fal fa-bed') {
        //             $nbLit = filter_var($node->filter('span')->text(), FILTER_SANITIZE_NUMBER_INT);
        //             $annonce->setNbLit($nbLit);
        //         }

        //         if ($node->filter('i')->attr('class') == 'fal fa-bath') {
        //             $nbDouche = filter_var($node->filter('span')->text(), FILTER_SANITIZE_NUMBER_INT);
        //             $annonce->setNbDouche($nbDouche);
        //         }

        //         if ($node->filter('i')->attr('class') == 'fal fa-cube') {
        //             $dimension = $node->filter('span')->text();
        //             $dimension = explode(" ", $node->filter('span')->text())[0];
        //             $dimension = filter_var($dimension, FILTER_SANITIZE_NUMBER_INT);
        //             $annonce->setDimension($dimension);
        //         }
        //     });

        //     $annonce->setIsForOwnerSite(false);
        //     $annonce->setTitle($title);
        //     $annonce->setEtiquette($etiquette);
        //     $annonce->setMontant($montant);
        //     $annonce->setLieu($lieu);
        //     $annonce->setLinkUrl($linkOfAnnonce);

        //     // get type Annonce
        //     $type = $this->repoType->findOneByLibelle('Location');
        //     $annonce->setTypeAnnonce($type);
        //     $this->em->persist($annonce);

        //     // set Image
        //     $image = new Image();
        //     $image->setUrl($img);
        //     $image->setAnnonce($annonce);
        //     $this->em->persist($image);

        //     $this->em->flush(); // save all data
        // });

        /**
         * Scraping du site expat.com
         */
        $crawler = $client->request('GET', 'https://www.expat.com/fr/immobilier/rechercheresultat/cHJpbWFyeS1maWx0ZXJ8MnwyfGZyfDE1MHwxMTYzMHwyMjg3NzgxfEPDtHRlIGQmIzM5O0l2b2lyZXwxfDJ8fDl8fHx8/');
        $crawler->filter('div.classified-wrapper')->each(function($node, $i) {

            // Scraping get element
            $linkOfAnnonce = $node->children()->filter('a')->first()->attr('href');


            // dd( html_entity_decode("display: block; background-image: url(&quot;https://www.expat.com/upload/housing/736316/1678187830541_3001865-housing_list-t1678187963.jpg&quot;);") );
            // if(str_contains($node->children()->filter('a')->first()->filter('div')->attr('style'), 'quot') ){
            //     echo("Word Found!" . PHP_EOL) ;
            // } else{
            //     echo("Word Not Found!") ;
            // }


            $img = $this->get_string_between( html_entity_decode( $node->children()->filter('a')->first()->filter('div')->attr('style') ), '(', ')' );

            if ($i == 1) {
                dd( $node->children()->filter('a')->first()->filter('div')->attr('data-title'),  $img );
            }

            

            $lieu = $node->children()->filter('div.classified-wrapper__content > span.classified-wrapper__location')->text();
            $etiquette = $node->children()->filter('div.classified-wrapper__content > span.classified-wrapper__price')->text();
            $title = $node->children()->filter('div.classified-wrapper__content > span.classified-wrapper__title > a')->text();
            $montant = $montant = filter_var($etiquette, FILTER_SANITIZE_NUMBER_INT);

            // set Annonce
            $annonce = new Annonce();
            $annonce->setIsForOwnerSite(false);
            $annonce->setTitle($title);
            $annonce->setEtiquette($etiquette);
            $annonce->setMontant($montant);
            $annonce->setLieu($lieu);
            $annonce->setLinkUrl($linkOfAnnonce);

            // get type Annonce
            $type = $this->repoType->findOneByLibelle('Location');
            $annonce->setTypeAnnonce($type);
            $this->em->persist($annonce);

            // set Image
            $image = new Image();
            $image->setUrl($img);
            $image->setAnnonce($annonce);
            $this->em->persist($image);

            // dd( $image );

            // $this->em->flush(); // save all data
        });

        return $this->json([
            'status' => 200,
            'message' => 'Opération éffectuée avec succès',
        ]);
    }

    public function get_string_between($string, $start, $end) {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

}
