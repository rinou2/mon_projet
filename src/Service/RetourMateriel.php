<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use App\Entity\User; 
use Symfony\Component\Mailer\MailerInterface;

class RetourMateriel
{
    private const SENDER_EMAIL = 'info.vitegourmand@gmail.com';
    public function __construct(         
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {} 
    public function sendRetourMateriel(User $user): void 
    {
        $email = (new TemplatedEmail())
            ->from(new Address(self::SENDER_EMAIL))
            ->to($user->getEmail())
            ->subject('En attente de retour de matériel !')
            ->htmlTemplate('retourmateriel/index.html.twig')
            ->context([                 
                'nom' => $user->getNom(),
            ]); 

        $this->mailer->send($email);
    }
}