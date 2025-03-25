<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\WishlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Item;
use App\Entity\Wishlist;
use Twig\TokenParser\UseTokenParser;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager, WishlistRepository $wishlistRepository, UserRepository $userRepository): Response
    {
        $topItems = $wishlistRepository->mostExpensiveItems();
        $topWishlists = $wishlistRepository->mostExpensiveLists();
        $users = $userRepository->findAll(); // Get all users


        if (!$topItems) {
            $topItems = []; 
        }
        
        if (!$topWishlists) {
             $topWishlists = []; 
        }
        return $this->render('admin/dashboard.html.twig', [
            'topItems' => $topItems,
            'topWishlists' => $topWishlists,
            'users' => $users,
        ]);
    }

    #[Route('/admin/users', name: 'admin_users')]
    public function manageUsers(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/user/{id}/lock', name: 'admin_user_lock', methods: ['POST'])]
    public function lockUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setIsLocked(true);
        $entityManager->flush();

        return $this->redirectToRoute('admin_users');
    }

    #[Route('/admin/user/{id}/unlock', name: 'admin_user_unlock', methods: ['POST'])]
    public function unlockUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setIsLocked(false);
        $entityManager->flush();

        return $this->redirectToRoute('admin_users');
    }

    #[Route('/admin/user/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_users');
    }

}
