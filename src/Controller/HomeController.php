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
        return $this->render('home/index.html.twig', [
            'links' => [
                'Register' => $this->generateUrl('register'),
                'Login' => $this->generateUrl('login'),
                'My Wishlists' => $this->generateUrl('app_wishlist_index'),
                'Admin Dashboard' => $this->generateUrl('admin_dashboard'),
                        ],
        ]);
    }
}