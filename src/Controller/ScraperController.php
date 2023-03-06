<?php

namespace App\Controller;

use Goutte\Client;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ScraperController extends AbstractController
{
    #[Route('/scraper', name: 'app_scraper')]
    public function index(): JsonResponse
    {

        $client = new Client();

        // $crawler = $client->request('GET', 'https://www.symfony.com/blog/');
        // Get the latest post in this category and display the titles
        // $dom = $crawler->filter('h2 > a')->each(function ($node) {
        //     return $node;
        // });
        // dd( $dom );

        // Vérifiez si le nœud actuel correspond à un sélecteur :
        // $crawler->matches('p.lorem');

        // Make requests with the request() method:

        $crawler = $client->request('GET', 'https://www.monimmo24.com');
        // $crawler->filter('.geodir-category-listing.fl-wrap')->each(function ($node) {
        //     $link = $node->children()->eq(0)->filter('a')->eq(0)->attr("href");
        //     $img = $node->children()->eq(0)->filter('a')->eq(0)->children()->filter('img')->attr('src');
        //     $lieu = $node->children()->eq(0)->children()->eq(1)->children()->filter('span')->text();
        //     $title = $node->children()->eq(0)->children()->filter('a')->last()->attr('data-ltitle');
        //     $nbLit = $node->children()->eq(1)->children()->filter('div.geodir-category-content-details > ul > li > span')->eq(0)->text();
        //     $nbDouche = $node->children()->eq(1)->children()->filter('div.geodir-category-content-details > ul > li > span')->eq(1)->text();
        //     $dimension = $node->children()->eq(1)->children()->filter('div.geodir-category-content-details > ul > li > span')->eq(2)->text();

            
        //     echo $link;
        // });

        // $crawler->filter('div')->children()->extract(array('class')) 

        // $res = $crawler->filter('.mh-property')->each(function ($node) {
        //     print '$i++;'. PHP_EOL;
        //     // print $node->attr('class');
        //     // dd( $node->attr('class') );
        // });

        dd(  $crawler->filter('.geodir-category-listing.fl-wrap')->children()->eq(0)->children()->filter('a')->last()->attr('data-lprice') );
        // dd(  $crawler->filter('.geodir-category-listing.fl-wrap')->children()->eq(0)->first()->filter('a')->eq(5)->attr("data-ltitle") );

        // $crawler = $crawler
        //     ->filter('body > p')
        //     ->reduce(function (Crawler $node, $i) {
        //         // filters every other node
        //         return ($i % 2) == 0;
        //     });

        // $res = $crawler->filter('div > div')->children();





        // $crawler->filter('.product-item__bottom')->each(function($node){
        //     $name = $node->filter('.product-item-name-js')->text();
        //     $price = $node->filter('.product-price-js')->text();
        //     $link = $node->filter('a.product-item-name-js')->attr('href');
        // });

        // $link = $node->filter('a.product-item-name-js')->attr('href');
        
        // $crawler->filter(' div.mh-caption__inner mh-label__rent')->each(function ($node) {
        //     dd( $node ); 
        // });

        // Click on links:
        // $link = $crawler->selectLink('Security Advisories')->link();
        // $crawler = $client->click($link); 

        // Extract data:
        // $crawler->filter('h2 > a')->each(function ($node) {
        //     print $node->text()."\n";
        // });

        // $contentOB =  $crawler->filter('h2 > a')->each(function ($node) {
        //     return $node->text()."\n";
        // });


        // dd( $res );

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ScraperController.php',
        ]);
    }
}
