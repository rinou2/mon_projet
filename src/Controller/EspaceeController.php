<?php

namespace App\Controller;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use  App\Entity\Commande;
use  App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CommandeRepository;
use App\Repository\AvisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EspaceeController extends AbstractController
{
    private const status= ['en attente', 'en cours de préparation', 'terminée', 'acceptée','livrée','annulée'];
    
    #[Route('/espacee/commandes', name: 'app_espacee_commandes')]
    public function commande(MenuRepository $menuRepository, CommandeRepository $commandeRepository): Response
    {
        $menus = $menuRepository->findAll();
        $commandes = $commandeRepository->findAll();

        return $this->render('espacee/commandes.html.twig', [
            'controller_name' => 'EspaceeController',
            'menu' => $menus,
            'commandes' => $commandes,
            'status' => self::status
        ]);
    }
    
    #[Route('/espacee', name: 'app_espacee')]
    public function index(): Response
    {
        return $this->render('espacee/index.html.twig', [
            'controller_name' => 'EspaceeController'
        ]);
    }
    #[Route('/espacee/statut{id}', name: 'app_statutem')]
    public function statut(EntityManagerInterface $entityManager,Request $request, Commande $commande ,int $id,CommandeRepository $commandeRepository,MenuRepository $menuRepository): Response
    {
        $statut = (string)$request->request->get('statut');
        //$menus = $menuRepository->findAll();
        $commande= $commandeRepository->find($id);
        $commande->setStatut($statut);
        $entityManager->persist($commande);
        $entityManager->flush();
       return $this->redirectToRoute('app_espacee_commandes');

        /*return $this->render('espacee/commandes.html.twig', [
            'controller_name' => 'EspaceeController',
             //'menu' => $menus,
            'commandes' => $commande,
            'status' => self::status]);*/
    }
    private const status2= ['en attente', 'publié','annulé'];
    #[Route('/espacee/avis', name: 'app_espacee_avis_list')]
    public function avis_list2(AvisRepository $avisRepository): Response
    {
        $avis = $avisRepository->findAll();
        return $this->render('espacee/avis.html.twig', [
            'controller_name' => 'EspaceeController',
            'avis' => $avis,
            'status' => self::status2
        ]);
    }
    #[Route('/espacee/status{id}', name: 'app_status')]
    public function status(EntityManagerInterface $entityManager,Request $request, AvisRepository $avisRepository ,int $id): Response
    {
        $statut = (string)$request->request->get('statut');
        $avis= $avisRepository->find($id);
        $avis->setStatut($statut);
        $entityManager->persist($avis);
        $entityManager->flush();
       return $this->redirectToRoute('app_espacee_avis_list');

    }

}

