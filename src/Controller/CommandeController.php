<?php

namespace App\Controller;
use App\Service\DeliveryCalculatorService;
use App\Entity\Commande;
use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CommandeRepository;
use App\Repository\MenuRepository; 

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommandeController extends AbstractController
{
    #[Route('/commande{id}', name: 'app_commande')]
    public function index(int $id, MenuRepository $menuRepository): Response
    {
        $menu = $menuRepository->find($id);
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
            'menu' => $menu
        ]);
    }
    #[Route('/com/{id}', name: 'app_com')]
    public function commande(EntityManagerInterface $entityManager ,
         Request $request,
         DeliveryCalculatorService $deliveryCalculator,
         MenuRepository $menuRepository,
         int $id
         )
         : Response


{
        $commande = new Commande() ;
        $tab=[
            'date_event'=>(string)$request->request->get('date_event'),
            'adresse'=>(string)$request->request->get('ad'),
            'lieu'=>(string)$request->request->get('lieu'),
            'heure'=>(string)$request->request->get('heure'),
            'nbr'=>(integer)$request->request->get('nbr'),
            'total'=>(float)$request->request->get('total')

        ];
        $frais= $deliveryCalculator->calculateDeliveryCost($tab['adresse'], $tab['lieu']);
        $menu = $menuRepository->find($id); 
        $user = $this->getUser(); 

        $commande->setDateEvent(new \DateTime($tab['date_event']));
        $commande->setAdresse($tab['adresse']);
        $commande->setLieu($tab['lieu']);
        $commande->setHeure(new \DateTime($tab['heure']));
        $commande->setNbrPersonnes($tab['nbr']);
        $commande->setTotal($menu->getPrix() * $tab['nbr'] + $frais);
        $commande->setDateCreation(new \DateTime());
        $commande->setStatut('en attente');
        $commande->setIdUser($user);
        $commande->setIdMenu($menu);
        $entityManager->persist($commande);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_espaceu',[
            'id' => $menu->getId()
        ]);
        
    }

}
