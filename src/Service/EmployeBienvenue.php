<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use App\Entity\Employer; 
use Symfony\Component\Mailer\MailerInterface;

class EmployeBienvenue
{
    private const SENDER_EMAIL = 'info.vitegourmand@gmail.com';
    public function __construct(         
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {} 
    public function sendEmployeBienvenue(Employer $employer): void 
    {
        $email = (new TemplatedEmail())
            ->from(new Address(self::SENDER_EMAIL))
            ->to($employer->getEmail())
            ->subject('Bienvenue chez Vite et Gourmand !')
            ->htmlTemplate('employebienvenue/index.html.twig')
            ->context([                 
                'nom' => $employer->getNom()

            ]); 

        $this->mailer->send($email);
    }
}