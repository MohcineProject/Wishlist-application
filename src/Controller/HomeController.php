<?php

namespace App\Controller;

use App\Entity\Invitation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(Request $request , EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser(); // Récupère l'utilisateur connecté

        $links = [
        ];


        $invitation_token = $request->getSession()->get("invitation_token");
        if($invitation_token){
        $invitation_id = InvitationController::verifyJointCreationToken($invitation_token);


        if ($invitation_id) {
            $user = $this->getUser();
             if ($user) {
                $invitation = $entityManager->find(Invitation::class, $invitation_id) ;
                if ($invitation) {
                    $user->addInvitation($invitation);
                    $entityManager->flush();
                }
                $request->getSession()->remove("invitation_token");
             }

        }
        }

        // Ajoutez le lien "Admin Dashboard" uniquement si l'utilisateur est admin
        if ($user && $user->isAdmin()) {
            $links['Admin Dashboard'] = $this->generateUrl('admin_dashboard');
        }

        if (!$user) {
            $links['Register'] = $this->generateUrl('register');
            $links['Login'] = $this->generateUrl('login');
        }

        if ($user) {
            
            $links['My Wishlists'] = $this->generateUrl('app_wishlist_index');
            $links['Profile'] = $this->generateUrl('user_profile');
            $links['Logout'] = $this->generateUrl('logout');
            $links['See my purchase proofs'] = $this->generateUrl('user_purchase_proofs');
            $links['See my invitations'] = $this->generateUrl('app_invitation_index');
        
            

            

        }

        return $this->render('home/index.html.twig', [
            'links' => $links,
        ]);
    }
}