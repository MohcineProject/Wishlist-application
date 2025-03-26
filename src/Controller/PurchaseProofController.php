<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\PurchaseProof;
use App\Form\PurchaseProofType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/item/{id}/purchaseproof')]
class PurchaseProofController extends AbstractController
{
    #[Route('/new', name: 'purchaseproof_new', methods: ['GET', 'POST'])]
    public function new(Item $item, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($item->getPurchaseProof()) {
            $this->addFlash('warning', 'This item already has a purchase proof.');
            return $this->redirectToRoute('app_item_show', ['id' => $item->getId()]);
        }

        $purchaseProof = new PurchaseProof();
        $purchaseProof->setItem(item: $item);
        
        $form = $this->createForm(PurchaseProofType::class, $purchaseProof);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imagePath')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move($this->getParameter('uploads_directory'), $newFilename);
                $purchaseProof->setImagePath($newFilename);
            }

            $entityManager->persist($purchaseProof);
            $entityManager->flush();

            return $this->redirectToRoute('app_item_show', ['id' => $item->getId()]);
        }

        return $this->render('purchase_proof/new.html.twig', [
            'form' => $form->createView(),
            'item' => $item,
        ]);
    }

    
}
