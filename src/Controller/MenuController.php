<?php

namespace App\Controller;
use App\Repository\MenuRepository; 

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(MenuRepository $menuRepository): Response
    {
        $menus = $menuRepository->findAll();

        return $this->render('menu/index.html.twig', [
            'menus' => $menus,
            'controller_name' => 'MenuController',
        ]);
    }
    
}
