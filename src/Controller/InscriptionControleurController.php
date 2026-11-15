<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\EmailVerifier;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface; 
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
    #[Route('/connexion/reinitialisation/{email}', name: 'app_reinitialisation')]
    public function reinitialisation(EntityManagerInterface $entityManager ,
         Request $request , string $email,
         UserPasswordHasherInterface $passwordHasher):Response
    {
        if($request->isMethod('POST')){
            $user=$entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            $pass =(string)$request->request->get('mdp');
            $user->setPassword($passwordHasher->hashPassword($user, $pass));
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_connexion');
        }
        return $this->render('connexion/reinitialisation.html.twig', [
            'email' => $email
        ]);
        
        
    }
    #[Route('/inscri', name: 'app_inscri')]
    public function inscri(EntityManagerInterface $entityManager ,
         Request $request ,
         UserPasswordHasherInterface $passwordHasher,
         EmailVerifier $emailVerifier)
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
        
        try {
            $emailVerifier->sendActivationEmail($user);
            $this->addFlash(
                'success',
                'Inscription réussie ! Consultez votre boîte e-mail pour activer votre compte.'
            );
        } catch (TransportExceptionInterface $exception) {
            $this->addFlash(
                'warning',
                "Votre compte a bien été créé, mais l'e-mail d'activation n'a pas pu être envoyé."
            );
        }
        
       
        return $this->redirectToRoute('app_connexion');
        
        
    }

}
 