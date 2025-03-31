<?php
// Edited By Néo Schobert & Yanis Bentoumi
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(Request $request , AuthenticationUtils $authenticationUtils): Response
    {

        $invitation_token = $request->get('invitation_token');

        if ($invitation_token) {
            $request->getSession()->set('invitation_token',$invitation_token) ;
        }
        // Récupère les erreurs de connexion, s'il y en a
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupère le dernier email saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        // Ce contrôleur peut rester vide, Symfony gère la déconnexion automatiquement
    }
}
