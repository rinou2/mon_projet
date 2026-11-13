<?php

namespace App\Controller;
use App\Repository\CommandeRepository;
use App\Repository\AvisRepository;
use App\Repository\MenuRepository;
use App\Entity\Avis;
use App\Entity\User;
use App\Entity\Commande;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EspaceuController extends AbstractController
{
    #[Route('/espaceu/commandes', name: 'app_espaceu_commandes')]
    public function commande(CommandeRepository $commandeRepository): Response
    {
        $commandes = $commandeRepository->findAll();
        return $this->render('espaceu/commandes.html.twig', [
            'controller_name' => 'EspaceuController',
            'commandes' => $commandes
        ]);
    }
    #[Route('/espaceu/avis', name: 'app_espaceu_avis_list')]
    public function avis_list(AvisRepository $avisRepository): Response
    {
        $avis = $avisRepository->findAll();
        return $this->render('espaceu/avis.html.twig', [
            'controller_name' => 'EspaceuController',
            'avis' => $avis
        ]);
    }
    #[Route('/espaceu', name: 'app_espaceu')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('espaceu/index.html.twig', [
            'controller_name' => 'EspaceuController',
            'user' => $user
        ]);
    }
    #[Route('/espaceu/avis/{id}', name: 'app_espaceu_avis')]
    public function avis(EntityManagerInterface $entityManager ,
         Request $request,CommandeRepository $commandeRepository, int $id): Response
    {
        $avis = new Avis() ;
        $commande=$commandeRepository->find($id);
        $tab=[
            'commentaire'=>(string)$request->request->get('comment'),
            'note'=>(integer)$request->request->get('note')

        ];

        $avis->setCommentaire($tab['commentaire']);
        $avis->setNote($tab['note']);
        $avis->setUser($this->getUser());
        $avis->setUserNom($this->getUser()->getNom());
        $avis->setCommande($commande);
        $avis->setStatut('envoyé');
        $entityManager->persist($avis);
        $entityManager->flush();
        return $this->redirectToRoute('app_espaceu_commandes');
    }
    #[Route('/espaceu/{id}', name: 'app_modifier_infos', methods: ['POST'])]
    public function modifier(User $user,UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user->setNom((string)$request->request->get('nom'));
        $user->setEmail((string)$request->request->get('email'));
        $user->setNum((int)$request->request->get('num'));
        $user->setPostale((string)$request->request->get('ad'));
        $user->setPassword($passwordHasher->hashPassword($user, (string)$request->request->get('mdp')));
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_espaceu');

    }
    #[Route('/espaceu/details/{id}', name: 'app_commande_details')]
    public function details(int $id, CommandeRepository $commandeRepository): Response
    {
        $commande=$commandeRepository->find($id);
        return $this->render('espaceu/details_commande.html.twig', [
            'controller_name' => 'EspaceuController',
            'commande' => $commande
        ]);
    }
    #[Route('/espaceu/commandes/supprimer/{id}', name: 'app_supprimer_commande', methods: ['POST'])]
    public function supprimer(int $id, EntityManagerInterface $entityManager, CommandeRepository $commandeRepository): Response
    {
        $commande = $commandeRepository->find($id);
        $entityManager->remove($commande);
        $entityManager->flush();
        $this->addFlash('succes', 'La commande a été supprimée.');

        return $this->redirectToRoute('app_espaceu_commandes');
    }

}
