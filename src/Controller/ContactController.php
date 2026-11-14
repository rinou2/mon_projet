<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Contact;
use App\Service\EnvoieContact;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(EnvoieContact $envoieContact, Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $contact = new Contact();
        if ($request->isMethod('POST')) {
        $contact->setNom((string) $request->request->get('nom'));
        $contact->setEmail((string) $request->request->get('email'));
        $contact->setNum((string) $request->request->get('num'));
        $contact->setSujet((string) $request->request->get('sujet'));
        $contact->setMessage((string) $request->request->get('message'));
        $contact->setDateEnvoie(new \DateTime());
        $entityManager->persist($contact);
        $entityManager->flush();
        $envoieContact->sendContact($contact);
        return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}
