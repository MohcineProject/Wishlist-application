<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\PurchaseProof;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {

        $joint_creation_URL = isset($_GET["joint_creation_URL"]) ? $_GET["joint_creation_URL"] : null;
        $user = $this->getUser(); // Récupère l'utilisateur connecté

        $links = [
        ];

        if ($joint_creation_URL) {
            $links["joint_creation_URL"] = $joint_creation_URL;
        }

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
            $links['See my purchase proofs'] = $this->generateUrl('user_purchase_proofs');
        
            

            

        }

        return $this->render('home/index.html.twig', [
            'links' => $links,
        ]);
    }
}