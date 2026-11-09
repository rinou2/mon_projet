<?php
namespace App\Controller;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use  App\Entity\Commande;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
final class EspaceaController extends AbstractController
{
    #[Route('/espacea', name: 'app_espacea')]
    public function index(Request $request,CommandeRepository $commandeRepository): Response
    {
        $commandes = $commandeRepository->findAll();
        $tab = array_fill(0, 12, 0);
        foreach ($commandes as $commande) {
            $date=$commande->getDate_Creation();
            $month = (int)$date->format('m');
            $tab[$month-1]+=$commande->getTotal();
        }
        $periode = $request->query->get('periode', 12);

        $tabFiltre = array_slice($tab, -$periode);
        return $this->render('espacea/index.html.twig', [
            'controller_name' => 'EspaceaController',
            'tab'=>$tabFiltre
        ]);
    }
    private const status= ['en attente', 'en cours de préparation', 'terminée', 'acceptée','livrée','annulée'];
    #[Route('/espacea/commandes', name: 'app_espacea_commandes')]
    public function commande(MenuRepository $menuRepository, CommandeRepository $commandeRepository): Response
    {
        $menus = $menuRepository->findAll();
        $commandes = $commandeRepository->findAll();
        $tab = array_fill(0, 12, 0);
        foreach ($commandes as $commande) {
            $date=$commande->getDate_Creation();
            $month = (int)$date->format('m');
            $tab[$month-1]+=$commande->getTotal();
        }
        return $this->render('espacea/commandes.html.twig', [
            'controller_name' => 'EspaceaController',
            'menu' => $menus,
            'commandes' => $commandes,
            'status' => self::status,
            'tab'=>$tab
        ]);
    }

    #[Route('/espacea/statut{id}', name: 'app_statuta')]
    public function statut(EntityManagerInterface $entityManager,
    Request $request, Commande $commande ,int $id,CommandeRepository $commandeRepository): Response
    {
        $statut = (string)$request->request->get('statut');
        $commande= $commandeRepository->find( $id);
        $commande->setStatut($statut);
        $entityManager->persist($commande);
        $entityManager->flush();
        return $this->redirectToRoute('app_espacea_commandes');
    }
}
