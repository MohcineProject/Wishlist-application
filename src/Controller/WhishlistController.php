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
final class WhishlistController extends AbstractController
{
    #[Route(name: 'app_whishlist_index', methods: ['GET'])]
    public function index(WishlistRepository $wishlistRepository): Response
    {
        return $this->render('whishlist/index.html.twig', [
            'wishlists' => $wishlistRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_whishlist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wishlist = new Wishlist();
        $form = $this->createForm(WishlistType::class, $wishlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($wishlist);
            $entityManager->flush();

            return $this->redirectToRoute('app_whishlist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('whishlist/new.html.twig', [
            'wishlist' => $wishlist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_whishlist_show', methods: ['GET'])]
    public function show(Wishlist $wishlist): Response
    {
        return $this->render('whishlist/show.html.twig', [
            'wishlist' => $wishlist,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_whishlist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Wishlist $wishlist, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WishlistType::class, $wishlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_whishlist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('whishlist/edit.html.twig', [
            'wishlist' => $wishlist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_whishlist_delete', methods: ['POST'])]
    public function delete(Request $request, Wishlist $wishlist, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wishlist->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($wishlist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_whishlist_index', [], Response::HTTP_SEE_OTHER);
    }
}
