<?php

namespace App\Controller;
use App\Entity\Employer;
use App\Service\EmployeBienvenue;
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
    #[Route('/espacea/employes{id}', name: 'app_supprimer_employe')]
    public function supprimer(int $id, EntityManagerInterface $entityManager, EmployerRepository $employerRepository): Response
    {
        $employer = $employerRepository->find($id);
        $entityManager->remove($employer);
        $entityManager->flush();

        return $this->redirectToRoute('app_ajout');
    }
        #[Route('/aj', name: 'app_aj')]
    public function aj(EntityManagerInterface $entityManager ,
         Request $request ,
         UserPasswordHasherInterface $passwordHasher,
         EmployeBienvenue $employeBienvenue)
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
        $employeBienvenue->sendEmployeBienvenue($employer);
        return $this->redirectToRoute('app_espacea');
        
        
    }
}
