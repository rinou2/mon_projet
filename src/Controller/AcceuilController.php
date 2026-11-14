<?php

namespace App\Controller;
use App\Repository\AvisRepository;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AcceuilController extends AbstractController
{
    #[Route('/', name: 'app_acceuil')]
    public function index(AvisRepository $avisRepository, MenuRepository $menuRepository): Response
    {
        $avis = $avisRepository->findAll();
        // les menus de la base pour la section "Nos menus" de l'accueil
        $menus = $menuRepository->findAll();
        return $this->render('acceuil/index.html.twig', [
            'controller_name' => 'AcceuilController',
            'avis' => $avis,
            'menus' => $menus
        ]);
    }
}
