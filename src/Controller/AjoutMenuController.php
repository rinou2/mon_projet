<?php

namespace App\Controller;
use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MenuRepository;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AjoutMenuController extends AbstractController
{
    // page Menus de l'espace admin
    #[Route('/espacea/menus', name: 'app_ajout_menu')]
    public function index(MenuRepository $menuRepository): Response
    {
        $menus=$menuRepository->findAll();
        return $this->render('espacea/menu.html.twig', [
            'controller_name' => 'AjoutMenuController',
            'menus'=>$menus

        ]);
    }

    // page Menus de l'espace employe (meme chose que l'admin)
    #[Route('/espacee/menus', name: 'app_espacee_menus')]
    public function menusEmploye(MenuRepository $menuRepository): Response
    {
        $menus=$menuRepository->findAll();
        return $this->render('espacee/menu.html.twig', [
            'menus'=>$menus
        ]);
    }

    // ajouter un menu (admin ou employe)
    #[Route('/aj/menu', name: 'app_ajmenu')]
    #[IsGranted('ROLE_EMPLOYER')]
    public function ajmenu(EntityManagerInterface $entityManager ,
         Request $request)
         : Response


{
        $menu = new Menu() ;
        // plusieurs images possibles (input name="img[]")
        $images=$request->files->all('img');

        $tab=[
            'titre'=>(string)$request->request->get('tit'),
            'description'=>(string)$request->request->get('descr'),
            'nombre'=>(int)$request->request->get('nbr'),
            'prix'=>(float)$request->request->get('prix'),
            'theme'=>(string)$request->request->get('theme'),
            'regime'=>(string)$request->request->get('regime'),
            'entree'=>(string)$request->request->get('entree'),
            'plat'=>(string)$request->request->get('plat'),
            'dessert'=>(string)$request->request->get('dessert')

        ];

        $menu->setTitre($tab['titre']);
        $menu->setDescription($tab['description']);
        $menu->setNbrPersonnes($tab['nombre']);
        $menu->setPrix($tab['prix']);

        $noms=[];
        foreach($images as $image){
            if($image){
                $im=uniqid().'.'.$image->guessExtension();
                $image->move($this->getParameter('kernel.project_dir').'/public/images',$im);
                $noms[]=$im;
            }
        }

        if(count($noms)>0){
            // la 1ere image est l'image principale (carte du menu)
            $menu->setImage($noms[0]);
            // toutes les images pour la galerie
            $menu->setImages($noms);
        }

        $menu->setTheme($tab['theme']);
        $menu->setRegime($tab['regime']);
        // [0] = entrée, [1] = plat, [2] = dessert
        $menu->setPlats([$tab['entree'], $tab['plat'], $tab['dessert']]);

        $entityManager->persist($menu);
        $entityManager->flush();

        // on revient sur la page Menus de son espace
        if($this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_ajout_menu');
        }
        return $this->redirectToRoute('app_espacee_menus');
    }

    // modifier un menu (admin ou employe)
    #[Route('/modif/menu/{id}', name: 'app_modif_menu', methods: ['POST'])]
    #[IsGranted('ROLE_EMPLOYER')]
    public function modifmenu(EntityManagerInterface $entityManager, Request $request, MenuRepository $menuRepository, int $id): Response
    {
        $menu=$menuRepository->find($id);

        if(!$menu){
            throw $this->createNotFoundException('Ce menu n\'existe pas.');
        }

        $images=$request->files->all('img');

        $tab=[
            'titre'=>(string)$request->request->get('tit'),
            'description'=>(string)$request->request->get('descr'),
            'nombre'=>(int)$request->request->get('nbr'),
            'prix'=>(float)$request->request->get('prix'),
            'theme'=>(string)$request->request->get('theme'),
            'regime'=>(string)$request->request->get('regime'),
            'entree'=>(string)$request->request->get('entree'),
            'plat'=>(string)$request->request->get('plat'),
            'dessert'=>(string)$request->request->get('dessert')
        ];

        $menu->setTitre($tab['titre']);
        $menu->setDescription($tab['description']);
        $menu->setNbrPersonnes($tab['nombre']);
        $menu->setPrix($tab['prix']);
        $menu->setTheme($tab['theme']);
        $menu->setRegime($tab['regime']);
        // [0] = entrée, [1] = plat, [2] = dessert
        $menu->setPlats([$tab['entree'], $tab['plat'], $tab['dessert']]);

        // nouvelles images : seulement si on en a choisi, sinon on garde les anciennes
        $noms=[];
        foreach($images as $image){
            if($image){
                $im=uniqid().'.'.$image->guessExtension();
                $image->move($this->getParameter('kernel.project_dir').'/public/images',$im);
                $noms[]=$im;
            }
        }

        if(count($noms)>0){
            $menu->setImage($noms[0]);
            $menu->setImages($noms);
        }

        // pas besoin de persist : le menu existe deja
        $entityManager->flush();

        if($this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_ajout_menu');
        }
        return $this->redirectToRoute('app_espacee_menus');
    }

    // supprimer un menu (admin ou employe)
    #[Route('/supp/menu/{id}', name: 'app_supp_menu', methods: ['POST'])]
    #[IsGranted('ROLE_EMPLOYER')]
    public function suppmenu(EntityManagerInterface $entityManager, MenuRepository $menuRepository, CommandeRepository $commandeRepository, int $id): Response
    {
        $menu=$menuRepository->find($id);

        if(!$menu){
            throw $this->createNotFoundException('Ce menu n\'existe pas.');
        }

        // on ne peut pas supprimer un menu qui a deja des commandes
        $commandes=$commandeRepository->findBy(['id_menu'=>$menu]);

        if(count($commandes)>0){
            $this->addFlash('danger', 'Impossible de supprimer "'.$menu->getTitre().'" : ce menu a déjà des commandes.');
        } else {
            $entityManager->remove($menu);
            $entityManager->flush();
            $this->addFlash('success', 'Le menu a été supprimé.');
        }

        if($this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_ajout_menu');
        }
        return $this->redirectToRoute('app_espacee_menus');
    }
}
