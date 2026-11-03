<?php

namespace App\Controller;
use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class AjoutMenuController extends AbstractController
{
    #[Route('/espacea/menus', name: 'app_ajout_menu')]
    public function index(MenuRepository $menuRepository): Response
    {
        $menus=$menuRepository->findAll();
        return $this->render('espacea/menu.html.twig', [
            'controller_name' => 'AjoutMenuController',
            'menus'=>$menus

        ]);
    }
     #[Route('/aj/menu', name: 'app_ajmenu')]
    public function ajmenu(EntityManagerInterface $entityManager ,
         Request $request)
         : Response


{
        $menu = new Menu() ;
        $image=$request->files->get('img');
        
        $tab=[
            'titre'=>(string)$request->request->get('tit'),
            'description'=>(string)$request->request->get('descr'),
            'nombre'=>(integer)$request->request->get('nbr'),
            'prix'=>(double)$request->request->get('prix'),
            'theme'=>(string)$request->request->get('theme'),
            'regime'=>(string)$request->request->get('regime')

        ];

        $menu->setTitre($tab['titre']);
        $menu->setDescription($tab['description']);
        $menu->setNbrPersonnes($tab['nombre']);
        $menu->setPrix($tab['prix']);

        if($image){
            $im=uniqid().'.'.$image->guessExtension();
            
            $image->move($this->getParameter('kernel.project_dir').'/public/images',$im);
            $menu->setImage($im);
            
        }
        
        $menu->setTheme($tab['theme']);
        $menu->setRegime($tab['regime']);
        
        $entityManager->persist($menu);
        $entityManager->flush();
        return $this->redirectToRoute('app_espacea');
        
        
    }
}
