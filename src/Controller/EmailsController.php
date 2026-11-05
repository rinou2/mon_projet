<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\EmailVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class EmailsController extends AbstractController
{
    #[Route('/activation', name: 'app_email', methods: ['GET'])]
    public function activate(
        Request $request,
        UserRepository $userRepository,
        EmailVerifier $emailVerifier
    ): Response {
        $id = $request->query->get('id');

        if (null === $id) {
            $this->addFlash('danger', "Lien d'activation invalide.");

            return $this->redirectToRoute('app_connexion');
        }
        $user = $userRepository->find($id);

        if (null === $user) {
            $this->addFlash('danger', "Lien d'activation invalide.");

            return $this->redirectToRoute('app_connexion');
        }
        if ($user->isVerified()) {
            $this->addFlash('success', 'Votre compte est déjà activé.');

            return $this->redirectToRoute('app_connexion');
        }
        try {
            $emailVerifier->activate($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('danger', 'Ce lien d\'activation est invalide ou a expiré.');

            return $this->redirectToRoute('app_connexion');
        }

        $this->addFlash('success', 'Votre compte est activé ! Vous pouvez vous connecter.');

        return $this->redirectToRoute('app_connexion');
    }
}