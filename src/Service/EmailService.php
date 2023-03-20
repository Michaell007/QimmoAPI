<?php

namespace App\Service;

use App\Repository\AnnonceRepository;
use App\Repository\SouscripteurRepository;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService {

    private $mailer;
    private $repoSouscripteur;
    private $repoAnnonce;
    public function __construct(MailerInterface $mailer, SouscripteurRepository $repoSouscripteur, AnnonceRepository $repoAnnonce)
    {
        $this->mailer = $mailer;
        $this->repoAnnonce = $repoAnnonce;
        $this->repoSouscripteur = $repoSouscripteur;
    }

    public function sender() {

        $list_souscripteurs = $this->repoSouscripteur->findAll();

        if ( count($list_souscripteurs) > 0 ) {

            foreach ($list_souscripteurs as $key => $souscripteur) {
                $ads = $this->repoAnnonce->findAnnonceBySouscripteurCriteres($souscripteur);
                if ( count($ads) ) {

                    // Send Email
                        $email = (new TemplatedEmail())
                        ->from( new Address('michaelakichi@gmail.com', 'Qimmo Immobilier') )
                        ->to(new Address($souscripteur->getEmail()))
                        ->subject('Nouvelle notification !')
                        // path of the Twig template to render
                        ->htmlTemplate('emails/notifications.html.twig')
                        // pass variables (name => value) to the template
                        ->context([
                            'annonces' => $ads,
                            'user' => $souscripteur,
                        ])
                    ;
                    // send email
                    $this->mailer->send($email);

                }
            }

        } else {
            dd( 'unpeu' );
        }

        

        

    }



}