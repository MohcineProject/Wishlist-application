<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté

        $links = [
        ];

        // Ajoutez le lien "Admin Dashboard" uniquement si l'utilisateur est admin
        if ($user && $user->isAdmin()) {
            $links['Admin Dashboard'] = $this->generateUrl('admin_dashboard');
        }

        if (!$user) {
            $links['Register'] = $this->generateUrl('register');
            $links['Login'] = $this->generateUrl('login');
        }

        if ($user) {
            $links['My Wishlists'] = $this->generateUrl('app_wishlist_index');
            $links['Profile'] = $this->generateUrl('user_profile');
            $links['Logout'] = $this->generateUrl('logout');

        }

        return $this->render('home/index.html.twig', [
            'links' => $links,
        ]);
    }
}