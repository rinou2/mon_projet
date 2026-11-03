<?php 
// src/Controller/TestEmailController.php 
// // TEMPORARY FILE: delete it at the end of step 3.   
namespace App\Controller;   
use Symfony\Bridge\Twig\Mime\TemplatedEmail; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Mailer\MailerInterface; 
use Symfony\Component\Mime\Address; 
use Symfony\Component\Routing\Attribute\Route;   

class EmailsController extends AbstractController {     
    #[Route('/test-email', name: 'app_test_email')]     
    public function send(MailerInterface $mailer): Response     {         
        $email = (new TemplatedEmail())             
        ->from(new Address('info.vitegourmand@gmail.com', 'Mon Projet'))             
        ->to('sirinearifa02@gmail.com')             
        ->subject('Test : e-mail de bienvenue')             
        ->htmlTemplate('emails/index.html.twig')             
        ->context([                 
            'user' => ['email' => 'sirinearifa02@gmail.com'],                 
            'signedUrl' => 'https://example.com/lien-de-test',                 
            'expiresAt' => new \DateTimeImmutable('+1 hour'),             
            ]);           
        $mailer->send($email);           
        return new Response('E-mail de test envoyé. Regardez votre boîte Gmail.');     
    } 
} 