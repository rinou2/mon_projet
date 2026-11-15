<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use App\Entity\User; 
use Symfony\Component\Mailer\MailerInterface;

class ForgetedPassword
{
    private const SENDER_EMAIL = 'info.vitegourmand@gmail.com';
    public function __construct(         
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {} 
    public function sendForgetedPassword(String $email): void 
    {
        $email = (new TemplatedEmail())
            ->from(new Address(self::SENDER_EMAIL))
            ->to($email)
            ->subject('Réinitialisation de votre mot de passe')
            ->htmlTemplate('connexion/mdp.html.twig')
            ->context([
                'userEmail' => $email,
            ]); 

        $this->mailer->send($email);
    }
}