<?php

namespace App\Repository;

use App\Entity\Evenements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenements>
 *
 * @method Evenements|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenements|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenements[]    findAll()
 * @method Evenements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenements::class);
    }

//    /**
//     * @return Evenements[] Returns an array of Evenements objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Evenements
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findNewEvents($limit = 3)
{
    return $this->createQueryBuilder('e')
        ->orderBy('e.dateEvent', 'DESC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}

public function findBySearchAndSort($searchBy, $searchQuery, $sortBy, $sortOrder)
    {
        $qb = $this->createQueryBuilder('e');

        if ($searchQuery && $searchBy) {
            $qb->andWhere('e.'.$searchBy.' LIKE :searchQuery')
            ->setParameter('searchQuery', '%'.$searchQuery.'%');
        }

        $qb->orderBy('e.'.$sortBy, $sortOrder);

        return $qb->getQuery()->getResult();
    }

<<<<<<< Updated upstream
=======
    public function findBySearch($searchQuery)
{
    $qb = $this->createQueryBuilder('e');

    if ($searchQuery) {
        $qb->andWhere('e.nomEvent LIKE :searchQuery')
           ->setParameter('searchQuery', '%'.$searchQuery.'%');
    }

    return $qb->getQuery()->getResult();
}

>>>>>>> Stashed changes
public function findByCategory(string $categorie): array
{
    return $this->createQueryBuilder('e')
        ->andWhere('e.categorieEvent = :categorie')
        ->setParameter('categorie', $categorie)
        ->getQuery()
        ->getResult();
}
public function countEventsByCategory()
{
    return $this->createQueryBuilder('e')
        ->select('e.categorieEvent, COUNT(e.idEvent) as count')
        ->groupBy('e.categorieEvent')
        ->getQuery()
        ->getResult();
}
}
