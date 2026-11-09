<?php

namespace App\Controller;
use App\Repository\CommandeRepository;
use App\Repository\AvisRepository;
use App\Repository\MenuRepository;
use App\Entity\Avis;
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
        return $this->render('espaceu/index.html.twig', [
            'controller_name' => 'EspaceuController'
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

}
