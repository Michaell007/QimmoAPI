<?php

namespace App\Controller;

use App\Service\EmailService;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerController extends AbstractController
{

    #[Route('/mailer', name: 'app_mailer')]
    public function index(MailerInterface $mailer, EmailService $envoieMail)
    {

        $envoieMail->sender();

        // $email = (new TemplatedEmail())
        //     ->from( new Address('michaelakichi@gmail.com', 'Qimmo Immobilier') )
        //     ->to(new Address('michaelakichi@gmail.com'))
        //     ->subject('Nouvelle notification !')
        //     // path of the Twig template to render
        //     ->htmlTemplate('emails/notifications.html.twig')
        //     // pass variables (name => value) to the template
        //     ->context([
        //         'expiration_date' => new \DateTime('+7 days'),
        //         'username' => 'foo',
        //     ])
        // ;
        // // send email
        // $mailer->send($email);

        return new Response();
    }
}
