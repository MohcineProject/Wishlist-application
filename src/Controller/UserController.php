<?php

namespace App\Controller;

use App\Entity\Wishlist;
use App\Form\WishlistType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PurchaseProofRepository;

use App\Form\UserType;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    #[Route('/user/dashboard', name: 'user_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $wishlists = $entityManager->getRepository(Wishlist::class)->findBy(['owner' => $user]);

        return $this->render('user/dashboard.html.twig', [
            'user' => $user,
            'wishlists' => $wishlists,
        ]);
    }

    #[Route('/user/wishlist/new', name: 'user_wishlist_new', methods: ['GET', 'POST'])]
    public function createWishlist(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wishlist = new Wishlist();
        $form = $this->createForm(WishlistType::class, $wishlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $wishlist->addAuthor($user);
            $user->addToAuthorWishlists($wishlist);

            $entityManager->persist($wishlist);
            $entityManager->flush();

            $this->addFlash('success', 'Wishlist créée avec succès.');
            return $this->redirectToRoute('user_dashboard');
        }

        return $this->render('user/wishlist_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/wishlist/{id}/delete', name: 'user_wishlist_delete', methods: ['POST'])]
    public function deleteWishlist(Wishlist $wishlist, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('delete', $wishlist);

        $entityManager->remove($wishlist);
        $entityManager->flush();

        $this->addFlash('success', 'Wishlist supprimée avec succès.');
        return $this->redirectToRoute('user_dashboard');
    }

    #[Route('/user/profile', name: 'user_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $token = $this->container->get('security.token_storage')->getToken();
        if ($token) {
            $user = $token->getUser();
            dump($user);
        } else {
            dump('Aucun token trouvé');
        }

        if ($user==null)
         {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // Créer le formulaire pour modifier les informations de l'utilisateur
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les modifications
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/purchase-proofs', name: 'user_purchase_proofs')]
    public function listPurchaseProofs(PurchaseProofRepository $purchaseProofRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to access this page.');
        }

        $purchaseProofs = $purchaseProofRepository->createQueryBuilder('pp')
            ->join('pp.item', 'i')
            ->join('i.wishlist', 'w')
            ->where('w.owner = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $this->render('user/purchase_proofs.html.twig', [
            'purchaseProofs' => $purchaseProofs,
        ]);
    }

    
}