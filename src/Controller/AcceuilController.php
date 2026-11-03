<?php

namespace App\Controller;
use App\Repository\AvisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AcceuilController extends AbstractController
{
    #[Route('/', name: 'app_acceuil')]
    public function index(AvisRepository $avisRepository): Response
    {
        $avis = $avisRepository->findAll();
        return $this->render('acceuil/index.html.twig', [
            'controller_name' => 'AcceuilController',
            'avis' => $avis
        ]);
    }
}
