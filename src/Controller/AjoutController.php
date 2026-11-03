<?php

namespace App\Controller;
use App\Entity\Employer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EmployerRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AjoutController extends AbstractController
{
    #[Route('/espacea/employes', name: 'app_ajout')]
    public function index(EmployerRepository $employerRepository): Response
    {
        $employes=$employerRepository->findAll();
        return $this->render('espacea/employes.html.twig', [
            'controller_name' => 'AjoutController',
            'employes'=>$employes
        ]);
    }
        #[Route('/aj', name: 'app_aj')]
    public function aj(EntityManagerInterface $entityManager ,
         Request $request ,
         UserPasswordHasherInterface $passwordHasher)
         : Response


{
        $employer = new Employer() ;
        $tab=[
            'nom'=>(string)$request->request->get('nom'),
            'email'=>(string)$request->request->get('email'),
            'ad'=>(string)$request->request->get('ad'),
            'num'=>(integer)$request->request->get('num'),
            'postale'=>(string)$request->request->get('ad')

        ];

        $pass =(string)$request->request->get('mdp');
        $employer->setEmail($tab['email']);
        $employer->setPassword($passwordHasher->hashPassword($employer, $pass));
        $employer->setNom($tab['nom']);
        $employer->setPostale($tab['ad']);
        $employer->setNum($tab['num']);
        $employer->setRoles(['ROLE_EMPLOYER']);
        
        $entityManager->persist($employer);
        $entityManager->flush();
        return $this->redirectToRoute('app_espacea');
        
        
    }
}
