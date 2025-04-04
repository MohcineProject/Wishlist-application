<?php

// Created by Mohcine Zahdi and Othmane Mounouar 

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\Wishlist;
use App\Form\InvitationType;
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
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user ) {
            return $this->createAccessDeniedException("Vous devez être connecté pour accéder à cette page.");
        }

        return $this->render('invitation/index.html.twig', [
            'invitations' => $user->getInvitations(),
        ]);
    }

    #[Route('/new', name: 'app_invitation_new', methods: ['GET', 'POST'])]
    public function createInvitation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invitation = new Invitation();
        
        $user = $this->getUser() ;

        $data = json_decode($request->getContent(),true) ;
        $wishlist_id = $data["wishlist_id"] ?? null ;

        if (!$wishlist_id) {
            return new JsonResponse(["error"=>"Missing wishlist_id"] , Response::HTTP_BAD_REQUEST) ; 
        }

        if (!$user) {
            return $this->createAccessDeniedException('User is not connected');
        }
        $wishlist = $entityManager->find(Wishlist::class, $wishlist_id);

        $existing_invitation = $entityManager->getRepository(Invitation::class)->findOneBy(['wishlist'=>$wishlist  , 'inviter'=>$user]) ;
        if (!$existing_invitation) {
            $invitation->setInviter($user);
            $invitation->setWishlist($entityManager->find(Wishlist::class, $wishlist_id));
            $entityManager->persist($invitation);
            $entityManager->flush();

        } else {
            $invitation = $existing_invitation ;
        }

        return new JsonResponse(["joint_creation_URL"=>  InvitationController::generateJointCreationURL($invitation->getId())] , Response::HTTP_CREATED);
        
  
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


    #[Route('/accept_invitation/{id}' , name: 'app_accept_invitation' , methods:['POST', 'GET'])]
    public function acceptInvitation(Invitation $invitation ,  EntityManagerInterface $entityManager ){
        $user = $this->getUser();
        if ($user) {
        $user->acceptInvitation($invitation->getId());
        $entityManager->flush();
        return $this->redirectToRoute('app_invitation_index', [], Response::HTTP_SEE_OTHER);
    } else {
        return $this->createAccessDeniedException("Vous ne pouvez pas accèder à cette API sans authentification!") ;
    }
    }


    #[Route("/reject_invitation/{id}"  , name: 'app_reject_invitation' , methods:['POST', 'GET'])] 
    public function rejectInvitation(Invitation $invitation , EntityManagerInterface $entityManager){

        $user = $this->getUser();
        if ($user) {
            $user->rejectInvitation($invitation->getId());
            $entityManager->flush();
            return $this->redirectToRoute('app_invitation_index', [], Response::HTTP_SEE_OTHER);
        }else {
            return $this->createAccessDeniedException("Vous ne pouvez pas accèder à cette API sans authentification!") ;
        }
    }





    
    private function generateJointCreationURL(int $invitation_id): string {
        $secretKey = 'top_secret_key_789/*-'; 
        $hash = hash_hmac('sha256', (string) $invitation_id, $secretKey);
        $token = base64_encode($invitation_id . '|' . $hash);
        
        $serverIp = $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';
        return sprintf('http://%s:8000/login?invitation_token=%s', $serverIp, rtrim(strtr($token, '+/', '-_'), '='));
    }
    

    public static function verifyJointCreationToken(string $token): ?int {
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

