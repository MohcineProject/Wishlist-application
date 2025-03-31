<?php
// Created by Firas Bouzazi and Mohammed Oun 

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use App\Repository\WishlistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/item')]
final class ItemController extends AbstractController
{
    #[Route(name: 'app_item_index', methods: ['GET'])]
    public function index(ItemRepository $itemRepository): Response
    {
        return $this->render('item/index.html.twig', [
            'items' => $itemRepository->findAll(),
        ]);
    }


    #[Route('/item/new', name: 'app_item_new')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        WishlistRepository $wishlistRepository
    ): Response {
        $item = new Item();
    
        // Récupérer l’ID de la wishlist depuis l’URL
        $wishlistId = $request->query->get('wishlistId');
        if ($wishlistId) {
            $wishlist = $wishlistRepository->find($wishlistId);
            if ($wishlist) {
                $item->setWishlist($wishlist);
            }
        }
    
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($item);
            $entityManager->flush();
    
            // ✅ Redirection vers la bonne wishlist
            return $this->redirectToRoute('app_wishlist_show', [
                'id' => $item->getWishlist()->getId()
            ]);
        }
    
        return $this->render('item/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    
    

    #[Route('/{id}', name: 'app_item_show', methods: ['GET'])]
    public function show(Item $item): Response
    {
        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_wishlist_show', [
                'id' => $item->getWishlist()->getId(),
            ], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_item_delete', methods: ['POST'])]
    public function delete(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        $wishlistId = $item->getWishlist()->getId();
    
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $entityManager->remove($item);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_wishlist_show', [
            'id' => $wishlistId
        ]);
    }
    
}