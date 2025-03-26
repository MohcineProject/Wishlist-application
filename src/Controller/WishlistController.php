<?php

namespace App\Controller;

use App\Entity\Wishlist;
use App\Form\WishlistType;
use App\Repository\WishlistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wishlist')]
final class WishlistController extends AbstractController
{
    // Method to display all wishlists for the currently logged-in user
    #[Route(name: 'app_wishlist_index', methods: ['GET'])]
    public function getWishLists(WishlistRepository $wishlistRepository): Response
    {
        $user = $this->getUser(); // Get the currently authenticated user

        return $this->render('wishlist/index.html.twig', [
            'wishlists' => $user->getWishlists()->toArray() // Pass the user's wishlists to the template
        ]);
    }

    // Method to create a new wishlist
    #[Route('/new', name: 'app_wishlist_new', methods: ['GET', 'POST'])]
    public function createWishlist(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wishlist = new Wishlist(); // Create a new Wishlist entity
        $form = $this->createForm(WishlistType::class, $wishlist); // Create a form for the Wishlist entity
        $form->handleRequest($request); // Handle the form submission

        if ($form->isSubmitted() && $form->isValid()) {
            $wishlist->setOwner($this->getUser());
            $entityManager->persist($wishlist); // Persist the new wishlist to the database
            $entityManager->flush(); // Save changes to the database

            return $this->redirectToRoute('app_wishlist_index', [], Response::HTTP_SEE_OTHER); // Redirect to the wishlist index page
        }

        return $this->render('wishlist/new.html.twig', [
            'wishlist' => $wishlist, // Pass the wishlist entity to the template
            'form' => $form, // Pass the form to the template
        ]);
    }

    #[Route('/{id}', name: 'app_wishlist_show', methods: ['GET'])]
public function show(Wishlist $wishlist, Request $request): Response
{
    $sortBy = $request->query->get('sort', 'price_asc');
    $searchQuery = $request->query->get('search', '');

    return $this->render('wishlist/show.html.twig', [
        'wishlist' => $wishlist,
        'view_mode' => 'grid',
        'sort_by' => $sortBy,
        'search_query' => $searchQuery,
    ]);
}

    

    // Method to edit an existing wishlist
    #[Route('/{id}/edit', name: 'app_wishlist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Wishlist $wishlist, EntityManagerInterface $entityManager): Response
    {
       
        
        $form = $this->createForm(WishlistType::class, $wishlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_wishlist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('wishlist/edit.html.twig', [
            'wishlist' => $wishlist,
            'form' => $form,
        ]);
        
    }

    // Method to delete a wishlist
    #[Route('/{id}', name: 'app_wishlist_delete', methods: ['POST'])]
    public function delete(Request $request, Wishlist $wishlist, EntityManagerInterface $entityManager): Response
    {
        // Validate the CSRF token before deleting the wishlist
        if ($this->isCsrfTokenValid('delete'.$wishlist->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($wishlist); // Remove the wishlist from the database
            $entityManager->flush(); // Save changes to the database
        }

        return $this->redirectToRoute('app_wishlist_index', [], Response::HTTP_SEE_OTHER); // Redirect to the wishlist index page
    }
}