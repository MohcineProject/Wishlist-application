<?php
// Created by Mohcine Zahdi and Othmane Mounouar
namespace App\Repository;

use App\Entity\Wishlist;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wishlist>
 */
class WishlistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct( $registry, Wishlist::class);
    }


    public function findByID($id) {
        return $this->find($id); 
    }
    
    public function addWishlist(Wishlist $wishlist) {
            $this->getEntityManager()->persist($wishlist) ; 
            $this->getEntityManager()->flush();
    }

    public function removeWishlist(int $wishlistId) {
        $wishlist = $this->find($wishlistId);
        if ($wishlist) {
            $this->getEntityManager()->delete($wishlist);
            $this->getEntityManager()->flush();
        }
    }

    public function editWishList(int $wishlistId, string $name, DateTime $deadline) {
        $wishlist = $this->find($wishlistId);
        if ($wishlist) {
            $wishlist->setName($name);
            $wishlist->setDeadline($deadline);
            $this->getEntityManager()->flush();
        }
        return $wishlist;
    }



    public function mostExpensiveLists() {
        $wishlists  = $this->findAll() ; 
        $rankings = array();
        foreach ($wishlists as $wishlist) {
            $total =  $wishlist->wishlistTotalPrice() ; 
            
            if (sizeof(($rankings)) < 3  ) {
                $rankings[] = ['wishlist' => $wishlist, 'total' => $total];
                usort($rankings, callback: function($a, $b) {return $a['total'] - $b['total'];});
            } else {
                for ($i = 0; $i < sizeof($rankings) ; $i++ ) {
                    if ($rankings[$i]['total'] < $total ) {
                        $rankings[$i] = ['wishlist' => $wishlist, 'total' => $total] ; 
                    }
                } 

            }
            
        }
        $result = array() ; 
        for ($i =  0 ; $i < sizeof($rankings) ; $i++ ) {
            $result[] = $rankings[$i]['wishlist'] ; 
        }
        return $result;
    }


    public function mostExpensiveItems() {
        $wishlists = $this->findAll() ; 
        $rankings = array();
        foreach ($wishlists as $wishlist) {
            $items = $wishlist->getItems();
            foreach ($items as $item) {
                if ($item->getPurchaseProof()) {
                    if (sizeof(($rankings)) < 3  ) {
                        $rankings[] = $item;
                        usort( $rankings, function($a, $b) {return $a->getPrice() - $b->getPrice();});
                    } else {
                        for ($i = 0; $i < sizeof($rankings) ; $i++ ) {
                            if ($rankings[i]->getPrice() < $item->getPrice() ) {
                                $rankings[i] = $item ; 
                            }
                        } 
        
                    }
                }
            }

        }
        return $rankings;
    }
  
}
