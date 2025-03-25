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
    #[Route(name: 'app_wishlist_index', methods: ['GET'])]
    public function getWishLists(WishlistRepository $wishlistRepository): Response
    {
        $user = $this->getUser() ; 


        return $this->render('wishlist/index.html.twig', [
            'wishlists' => $user->getWishlists()
        ]);
    }

    #[Route('/new', name: 'app_wishlist_new', methods: ['GET', 'POST'])]
    public function createWishlist(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wishlist = new Wishlist();
        $form = $this->createForm(WishlistType::class, $wishlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($wishlist);
            $entityManager->flush();

            return $this->redirectToRoute('app_wishlist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('wishlist/new.html.twig', [
            'wishlists' => $wishlist,
            'form' => $form,
        ]); 
        

    }

    #[Route('/{id}', name: 'app_wishlist_show', methods: ['GET'])]
    public function show(Wishlist $wishlist): Response
    {
        return $this->render('wishlist/show.html.twig', [
            'wishlist' => $wishlist,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_wishlist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Wishlist $wishlist, EntityManagerInterface $entityManager): Response
    {
        $wishlist->setName($request->get('name'));
        $wishlist->setDeadline($request->get('deadline')) ; 
        $entityManager->persist($wishlist);
        $entityManager->flush();
        return new Response('wishlist was modified successfully ', Response::HTTP_ACCEPTED) ;
/*         $form = $this->createForm(WishlistType::class, $wishlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_wishlist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('wishlist/edit.html.twig', [
            'wishlist' => $wishlist,
            'form' => $form,
        ]); */
    }

    #[Route('/{id}', name: 'app_wishlist_delete', methods: ['POST'])]
    public function delete(Request $request, Wishlist $wishlist, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wishlist->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($wishlist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_wishlist_index', [], Response::HTTP_SEE_OTHER);
    }


}


/* 
        $wishlist = new Wishlist();
        $name = $request->get(key: 'name');
        $wishlist->setName(name: $name); 
        $deadline = $request->get('deadline') ; 
        $wishlist->setDeadline($deadline);
        $this->getUser()->addToAuthorWhishlists($wishlist);
        $entityManager->persist($wishlist);
        $entityManager->persist($this->getUser()) ;
        $entityManager->flush(); */