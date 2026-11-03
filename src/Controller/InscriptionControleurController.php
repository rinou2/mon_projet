<?php

namespace App\Controller;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class InscriptionControleurController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index():Response
    {

        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionControleurController',
        ]);
        
    }
    #[Route('/inscri', name: 'app_inscri')]
    public function inscri(EntityManagerInterface $entityManager ,
         Request $request ,
         UserPasswordHasherInterface $passwordHasher)
         : Response


{
        $user = new User() ;
        $tab=[
            'nom'=>(string)$request->request->get('nom'),
            'email'=>(string)$request->request->get('email'),
            'ad'=>(string)$request->request->get('ad'),
            'num'=>(integer)$request->request->get('num'),
            'postale'=>(string)$request->request->get('ad')

        ];

        $pass =(string)$request->request->get('mdp');
        $user->setEmail($tab['email']);
        $user->setPassword($passwordHasher->hashPassword($user, $pass));
        $user->setNom($tab['nom']);
        $user->setPostale($tab['ad']);
        $user->setNum($tab['num']);
        $user->setRoles(['ROLE_USER']);
        
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_connexion');
        
        
    }

}
 