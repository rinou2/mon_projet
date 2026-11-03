<?php

namespace App\Controller;
use App\Repository\MenuRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'app_connexion')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('connexion/index.html.twig', [
            'error' => $error,
            'email' => $lastUsername,
        ]);
    }
    
    #[Route('/espace', name: 'app_espace')]
    public function espace():Response{
        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_espaceu');
        }

        if($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_espacea');
        }
        if ($this->isGranted('ROLE_EMPLOYER')){
            return $this->redirectToRoute('app_espacee');
        }
    }

    #[Route('/deconnexion', name: 'app_deconnexion')]
    public function logout():never
    {
        
        throw new \Exception('deconnexion');
    }
}

