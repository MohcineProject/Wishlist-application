
<?php


use App\Repository\InvitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/wishlist')]
class InvitationsController extends AbstractController{

    #[Route(path:'', name:'')]

    public function createInvitation( Request $request , InvitationRepository $invitationRepository): Response {
        $userId = $request->get("userId") ;
        $state = $request->get("state") ;
        $wishlistId = $request->get("wishlistId") ; 
        $invitationRepository->createInvitation($userId, $state, $wishlistId);
        // return $this->render("")
        

    }



}



?>

