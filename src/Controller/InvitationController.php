<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\Wishlist;
use App\Form\InvitationType;
use App\Repository\InvitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/invitation')]
final class InvitationController extends AbstractController
{
    #[Route(name: 'app_invitation_index', methods: ['GET'])]
    public function index(InvitationRepository $invitationRepository): Response
    {
        return $this->render('invitation/index.html.twig', [
            'invitations' => $invitationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_invitation_new', methods: ['GET', 'POST'])]
    public function createInvitation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invitation = new Invitation();
        
        $user = $this->getUser() ; 

        if (!$user) {
            return $this->createAccessDeniedException('User is not connected');
        } 

        $invitation->setInviter($user);
        $invitation->setWishlist($entityManager->find(Wishlist::class, $request->get(key: 'wishlist_id')));
        $entityManager->persist($invitation);
        $entityManager->flush();

        return new JsonResponse(["joint_creation_URL"=>  InvitationController::generateJointCreationURL($invitation->getId())] , Response::HTTP_CREATED);
        
  
    }

    #[Route('/{id}', name: 'app_invitation_show', methods: ['GET'])]
    public function show(Invitation $invitation): Response
    {
        return $this->render('invitation/show.html.twig', [
            'invitation' => $invitation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_invitation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invitation $invitation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InvitationType::class, $invitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_invitation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invitation/edit.html.twig', [
            'invitation' => $invitation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invitation_delete', methods: ['POST'])]
    public function delete(Request $request, Invitation $invitation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invitation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($invitation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_invitation_index', [], Response::HTTP_SEE_OTHER);
    }


    private function generateJointCreationURL(int $invitation_id): string {
        $secretKey = 'top_secret_key_789/*-'; 
        $hash = hash_hmac('sha256', (string) $invitation_id, $secretKey);
        $token = base64_encode($invitation_id . '|' . $hash);
        
        $serverIp = $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';
        return sprintf('http://%s?invitation_token=%s', $serverIp, rtrim(strtr($token, '+/', '-_'), '='));
    }
    

    private function verifyJointCreationToken(string $token): ?int {
        $secretKey = 'top_secret_key_789/*-'; 
    
        $token = strtr($token, '-_', '+/'); 
        $token = base64_decode($token);
    
        if (!$token) {
            return null; 
        }
    
        $parts = explode('|', $token);
        if (count($parts) !== 2) {
            return null; 
        }
    
        [$invitation_id, $hash] = $parts;
    
        $expectedHash = hash_hmac('sha256', $invitation_id, $secretKey);
    
        if (!hash_equals($expectedHash, $hash)) {
            return null; 
        }
    
        return (int) $invitation_id;
    }
    
}

      /* $form = $this->createForm(InvitationType::class, $invitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($invitation);
            $entityManager->flush();

            return $this->redirectToRoute('app_invitation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invitation/new.html.twig', [
            'invitation' => $invitation,
            'form' => $form,
        ]); */