<?php
// Edited By Néo Schobert & Yanis Bentoumi
namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }


    public function findTopExpensiveItems(): array
    {
        return $this->createQueryBuilder('i')  // Alias "i" pour Item
            ->orderBy('i.price', 'DESC')       // Trier par prix décroissant
            ->setMaxResults(3)                 // Limiter à 3 résultats
            ->getQuery()
            ->getResult();                     // Exécuter la requête
    }
    //    /**
    //     * @return Item[] Returns an array of Item objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Item
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
