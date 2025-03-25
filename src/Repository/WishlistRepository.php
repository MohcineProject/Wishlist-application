<?php

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
            $this->getEntityManager()->remove($wishlist);
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
    }



    public function mostExpensiveList() {
        $wishlists  = $this->findAll() ; 

        
        foreach ($wishlists as $wishlist) {
            $items = $wishlist->getItems();
        }
        

    }

    //    /**
    //     * @return Wishlist[] Returns an array of Wishlist objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Wishlist
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
