<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    #[Route('/user/dashboard', name: 'user_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $wishlists = $entityManager->getRepository(Wishlist::class)->findBy(['owner' => $user]);

        return $this->render('user/dashboard.html.twig', [
            'user' => $user,
            'wishlists' => $wishlists,
        ]);
    }

    #[Route('/user/wishlist/new', name: 'user_wishlist_new', methods: ['GET', 'POST'])]
    public function createWishlist(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wishlist = new Wishlist();
        $form = $this->createForm(WishlistType::class, $wishlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wishlist->setOwner($this->getUser());
            $entityManager->persist($wishlist);
            $entityManager->flush();

            $this->addFlash('success', 'Wishlist créée avec succès.');
            return $this->redirectToRoute('user_dashboard');
        }

        return $this->render('user/wishlist_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/wishlist/{id}/delete', name: 'user_wishlist_delete', methods: ['POST'])]
    public function deleteWishlist(Wishlist $wishlist, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('delete', $wishlist);

        $entityManager->remove($wishlist);
        $entityManager->flush();

        $this->addFlash('success', 'Wishlist supprimée avec succès.');
        return $this->redirectToRoute('user_dashboard');
    }
}
