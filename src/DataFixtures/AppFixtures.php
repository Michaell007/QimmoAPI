<?php

namespace App\DataFixtures;

use App\Entity\TypeAnnonce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

         /**
          * Enregistre les types d'nnonce pr defaut
          */
        // $arrayTypeAnnonce = ["Location", "Vente"];
        // for ($i=0; $i < 2; $i++) {
        //     $tAnnonce = new TypeAnnonce();
        //     $tAnnonce->setLibelle($arrayTypeAnnonce[$i]);
        //     $manager->persist($tAnnonce);
        // } 
        

        $manager->flush();
    }
}
