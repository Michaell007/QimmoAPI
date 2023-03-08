<?php

namespace App\Command;

use Goutte\Client;
use App\Entity\Image;
use App\Entity\Annonce;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeAnnonceRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:scraper-sites',
    description: 'Scraping des sites',
)]
class ScraperSitesCommand extends Command
{

    private $em;
    private $repoType;
    public function __construct(EntityManagerInterface $em, TypeAnnonceRepository $repoType)
    {
        $this->em = $em;
        $this->repoType = $repoType;
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // $arg1 = $input->getArgument('arg1');

        $client = new Client();

        /**
         * Scraping du site https://www.monimmo24.com
         */
        $crawler = $client->request('GET', 'https://www.monimmo24.com/?status=a-louer&llocs=&lcats%5B%5D=&search_term=');
        $crawler->filter('.geodir-category-listing.fl-wrap')->each(function ($node, $i) {

            // Scraping get element
            $title = $node->children()->eq(0)->children()->filter('a')->last()->attr('data-ltitle');
            $etiquette = $node->children()->eq(0)->children()->filter('a')->last()->attr('data-lprice');
            $etiquette = str_replace(' ', '', $etiquette);
            $montant = filter_var($etiquette, FILTER_SANITIZE_NUMBER_INT);
            $lieu = trim( $node->children()->eq(0)->children()->eq(1)->children()->filter('span')->text() );
            $linkOfAnnonce = $node->children()->eq(0)->filter('a')->eq(0)->attr("href");
            $img = $node->children()->eq(0)->filter('a')->eq(0)->children()->filter('img')->attr('src');

            // set Annonce
            $annonce = new Annonce();
            $node->children()->eq(1)->children()->filter('div.geodir-category-content-details > ul > li')->each(function($node) use ($annonce) {

                if ($node->filter('i')->attr('class') == 'fal fa-bed') {
                    $nbLit = filter_var($node->filter('span')->text(), FILTER_SANITIZE_NUMBER_INT);
                    $annonce->setNbLit($nbLit);
                }

                if ($node->filter('i')->attr('class') == 'fal fa-bath') {
                    $nbDouche = filter_var($node->filter('span')->text(), FILTER_SANITIZE_NUMBER_INT);
                    $annonce->setNbDouche($nbDouche);
                }

                if ($node->filter('i')->attr('class') == 'fal fa-cube') {
                    $dimension = $node->filter('span')->text();
                    $dimension = explode(" ", $node->filter('span')->text())[0];
                    $dimension = filter_var($dimension, FILTER_SANITIZE_NUMBER_INT);
                    $annonce->setDimension($dimension);
                }
            });

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

            $this->em->flush(); // save all data
        });

        /**
         * Scraping du site expat.com
         */
        $crawler = $client->request('GET', 'https://www.expat.com/fr/immobilier/rechercheresultat/cHJpbWFyeS1maWx0ZXJ8MnwyfGZyfDE1MHwxMTYzMHwyMjg3NzgxfEPDtHRlIGQmIzM5O0l2b2lyZXwxfDJ8fDl8fHx8/');
        $crawler->filter('div.classified-wrapper')->each(function($node) {

            // Scraping get element
            $linkOfAnnonce = $node->children()->filter('a')->first()->attr('href');
            if ($node->children()->filter('a')->first()->filter('div')->attr('style') == null) {
                $img = 'property.png';
            } else {
                $img = $this->get_string_between( $node->children()->filter('a')->first()->filter('div')->attr('style'), '(', ')' );
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

            $this->em->flush(); // save all data
        });

        $io->success('La commande a été effectuée avec succès...');
        return Command::SUCCESS;
    }

    public function get_string_between($string, $start, $end) {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    
}
