<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use App\Entity\User; 
use App\Entity\Commande;
use Symfony\Component\Mailer\MailerInterface;

class CommandeTerminee
{
    private const SENDER_EMAIL = 'info.vitegourmand@gmail.com';
    public function __construct(         
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {} 
    public function sendCommandeTerminee(User $user, Commande $commande): void 
    {
        $email = (new TemplatedEmail())
            ->from(new Address(self::SENDER_EMAIL))
            ->to($user->getEmail())
            ->subject('Votre commande est livrée !')
            ->htmlTemplate('commandeterminee/index.html.twig')
            ->context([                 
                'user' => $user, 
                'menu' => $commande->getMenuNom(),               
                'adresse' => $commande->getAdresse(),
                'lieu' => $commande->getLieu(),
                'date' => $commande->getDate_Event()->format('d/m/Y'),
                'heure' => $commande->getHeure(),
                'nbrPersonnes' => $commande->getNbr_Personnes(),
                'frais' => $commande->getFrais(),
                'remise' => $commande->getRemise(),
                'total' => $commande->getTotal()
            ]); 

        $this->mailer->send($email);
    }
}