<?php

namespace App\Controller;
use App\Service\ConfirmationCommande;

use App\Service\DeliveryCalculatorService;
use App\Entity\Commande;
use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CommandeRepository;
use App\Repository\MenuRepository; 
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommandeController extends AbstractController
{
    #[Route('/commande{id}/{n}/{prix}', name: 'app_commande')]
    public function index(int $id, int $n, float $prix,Request $request, DeliveryCalculatorService $deliveryCalculator, MenuRepository $menuRepository): Response
    {
        $menu = $menuRepository->find($id);
        $tab=[
            'adresse'=>(string)$request->request->get('ad'),
            'lieu'=>(string)$request->request->get('lieu'),
        ];
        $frais= $deliveryCalculator->calculateDeliveryCost($tab['adresse'], $tab['lieu']);
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
            'menu' => $menu,
            'nbr' => $n,
            'prix' => $prix,
            'frais' => $frais
        ]);
    }
    #[Route('/calculer-frais', name: 'app_calculer_frais', methods: ['POST'])]
    public function calculerFrais(Request $request, DeliveryCalculatorService $deliveryCalculator): JsonResponse
    {
        $adresse = $request->request->get('adresse', '');
        $lieu = $request->request->get('lieu', '');

        $frais = $deliveryCalculator->calculateDeliveryCost($adresse, $lieu);

        return new JsonResponse(['frais' => $frais]);
    }
    #[Route('/com/{id}/{n}', name: 'app_com')]
    public function commande(EntityManagerInterface $entityManager ,
         Request $request,int $n,
         ConfirmationCommande $confirmationCommande,
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
        $menu = $menuRepository->find($id);
        $frais= $deliveryCalculator->calculateDeliveryCost($tab['adresse'], $tab['lieu']);
        $remise=$tab['nbr']> $n +5?($menu->getPrix() * $tab['nbr'] )* 0.1:0; 
        $user = $this->getUser(); 

        $commande->setDateEvent(new \DateTime($tab['date_event']));
        $commande->setAdresse($tab['adresse']);
        $commande->setLieu($tab['lieu']);
        $commande->setHeure(new \DateTime($tab['heure']));
        $commande->setNbrPersonnes($tab['nbr']);
        $commande->setFrais($frais);
        $commande->setRemise($remise);
        $commande->setTotal($menu->getPrix() * $tab['nbr'] + $frais - $remise);
        $commande->setDateCreation(new \DateTime());
        $commande->setStatut('en attente');
        $commande->setIdUser($user);
        $commande->setUserNom($user->getNom());
        $commande->setIdMenu($menu);
        $commande->setMenuNom($menu->getTitre());
        $commande->setDateEnAttente(new \DateTime());
        $entityManager->persist($commande);
        $entityManager->flush();
        $confirmationCommande->sendConfirmationCommande($user, $commande);
        
        return $this->redirectToRoute('app_espaceu',[
            'id' => $menu->getId()
        ]);
        
    }

}
