<?php

// Created by Mohcine Zahdi and Othmane Mounouar 


namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gifts_purchase')]
final class GiftsPurchaseController extends AbstractController
{
    

    #[Route('/new', name: 'app_gifts_purchase_new', methods: ['GET', 'POST'])]
    public function craeteGiftsPurchase(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser() ;
        $data = json_decode($request->getContent(),true) ;
        $wishlist_id = $data["wishlist_id"] ?? null ;
        if (!$wishlist_id) {
            return new JsonResponse(["error"=>"Missing wishlist_id"] , Response::HTTP_BAD_REQUEST) ;
        }
        if (!$user) {
            return $this->createAccessDeniedException('User is not connected');
        }
        return new JsonResponse(["joint_creation_URL"=>  GiftsPurchaseController::generateGiftsPurchaseURL($wishlist_id)] , Response::HTTP_CREATED); 
  
    }

    
    private function generateGiftsPurchaseURL(int $wishlist_id): string {
        $secretKey = 'a_se_iu_çs/*-';
        $hash = hash_hmac('sha256', (string) $wishlist_id, $secretKey);
        $token = base64_encode($wishlist_id . '|' . $hash);
        $serverIp = $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';
        return sprintf('http://%s:8000/login?gifts_purchase=%s', $serverIp, rtrim(strtr($token, '+/', '-_'), '='));
    }
    

    public static function verifyGiftsPurchaseToken(string $token): ?int {
        $secretKey = 'a_se_iu_çs/*-';
        $token = strtr($token, '-_', '+/');
        $token = base64_decode($token);
        if (!$token) {
            return null;
        }
        $parts = explode('|', $token);
        if (count($parts) !== 2) {
            return null;
        }
        [$wishlist_id, $hash] = $parts;
        $expectedHash = hash_hmac('sha256', $wishlist_id, $secretKey);
        if (!hash_equals($expectedHash, $hash)) {
            return null;
        }
        return (int) $wishlist_id;
    }
    
    

}

