<?php
namespace App\Controller;
use App\Repository\MenuRepository;
use App\Repository\EmployerRepository;
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
    public function index(Request $request,CommandeRepository $commandeRepository,EmployerRepository $employerRepository,MenuRepository $menuRepository): Response
    {
        $commandes = $commandeRepository->findAll();
        // 6 par défaut on filttre 
        $periode = $request->query->getInt('periode', 6);

        // Les N derniers mois en partant d'aujourd'hui 
        $nomsMois = ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];
        $cles = [];   // ex: "2026-09" pour ne pas mélanger les années
        $labels = []; // ex: "sept." pour l'axe X du graphe
        for ($i = $periode - 1; $i >= 0; $i--) {
            $mois = new \DateTime("first day of -$i month");
            $cles[] = $mois->format('Y-m');
            $labels[] = $nomsMois[(int)$mois->format('m') - 1];
        }

        $tab = array_fill(0, $periode, 0);
        foreach ($commandes as $commande) {
            $date=$commande->getDate_Creation();
            $index = array_search($date->format('Y-m'), $cles);
            if ($index !== false) {
                $tab[$index]+=$commande->getTotal();
            }
        }

        // Carte 1 
        $revenusMois = $tab[$periode - 1];

        // Carte 2 
        $nbEmployes = 0;
        foreach ($employerRepository->findAll() as $employer) {
            if (!in_array('ROLE_ADMIN', $employer->getRoles())) {
                $nbEmployes++;
            }
        }

        // Carte 3 
        $commandesParMenu = [];
        foreach ($menuRepository->findAll() as $menu) {
            $commandesParMenu[$menu->getTitre()] = 0;
        }
        foreach ($commandes as $commande) {
            $commandesParMenu[$commande->getIdMenu()->getTitre()]++;
        }
        arsort($commandesParMenu); // le menu le plus commandé en premier

        return $this->render('espacea/index.html.twig', [
            'controller_name' => 'EspaceaController',
            'tab'=>$tab,
            'labels'=>$labels,
            'revenusMois'=>$revenusMois,
            'nbEmployes'=>$nbEmployes,
            'commandesParMenu'=>$commandesParMenu
        ]);
    }
    private const status= ['en attente','acceptée', 'en préparation' ,'en cours de livraison', 'livrée', 'terminée'];
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
        $maintenant = new \DateTime();

        match ($statut) {
            'acceptée' => $commande->setDateAcceptee($maintenant),
            'en préparation' => $commande->setDateEnPreparation($maintenant),
            'en cours de livraison' => $commande->setDateEnCoursDeLivraison($maintenant),
            'livrée' => $commande->setDateLivree($maintenant),
            'terminée' => $commande->setDateTerminee($maintenant),
        };
        $entityManager->persist($commande);
        $entityManager->flush();
        return $this->redirectToRoute('app_espacea_commandes');
    }
    
}
