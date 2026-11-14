<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address; 
use App\Entity\Contact;
use Symfony\Component\Mailer\MailerInterface;

class EnvoieContact
{
    private const SENDER_EMAIL = 'info.vitegourmand@gmail.com';
    public function __construct(         
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {} 
    public function sendContact(Contact $contact): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address(self::SENDER_EMAIL, 'Site Vite & Gourmand'))
            ->to(self::SENDER_EMAIL)
            ->subject('Contact : '.$contact->getSujet())
            ->htmlTemplate('contact/mail.html.twig')
            ->context([
                'contact' => $contact,
                'nom' => $contact->getNom(),
                'mail' => $contact->getEmail(),
                'num' => $contact->getNum(),
                'sujet' => $contact->getSujet(),
                'message' => $contact->getMessage(),]);
        if ($contact->getEmail()) { 
            $email->replyTo($contact->getEmail());
        }

        $this->mailer->send($email);
    }
}
            