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
    public function autoSend(MailerInterface $mailer, EmailService $envoieMail)
    {
        $envoieMail->sender();
        return new Response();
    }
}
