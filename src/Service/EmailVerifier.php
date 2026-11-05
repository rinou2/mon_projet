<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User; 
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    private const SENDER_EMAIL = 'info.vitegourmand@gmail.com';
    public function __construct(         
        private VerifyEmailHelperInterface $verifyEmailHelper,         
        private MailerInterface $mailer,         
        private EntityManagerInterface $entityManager    ,
    ) {} 
    public function sendActivationEmail(User $user): void 
    {
        $signature = $this->verifyEmailHelper->generateSignature(             
            'app_email',          // name of the activation route (step 4.4)             
            (string) $user->getId(),     // who the link is for             
            $user->getEmail(),           // the address being verified             
            ['id' => $user->getId()]     // added to the URL: ?id=12         
        );
        $email = (new TemplatedEmail())
            ->from(new Address(self::SENDER_EMAIL))
            ->to($user->getEmail())
            ->subject('Bienvenue ! Activez votre compte')
            ->htmlTemplate('emails/index.html.twig')
            ->context([                 
                'user' => $user,                 
                'signedUrl' => $signature->getSignedUrl(),                 
                'expiresAt' => $signature->getExpiresAt(),       
            ]); 

        $this->mailer->send($email);
    }
    public function activate(Request $request, User $user): void     {
        $this->verifyEmailHelper->validateEmailConfirmationFromRequest(             
            $request,             
            (string) $user->getId(),             
            $user->getEmail()         
        ); 
        $user->setIsVerified(true);         
        $this->entityManager->flush();     
    }
 
}
