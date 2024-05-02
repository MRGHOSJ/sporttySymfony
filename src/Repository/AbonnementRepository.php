<?php

namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Abonnement>
 *
 * @method Abonnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonnement[]    findAll()
 * @method Abonnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnement::class);
    }
// Dans votre AbonnementRepository

public function findMostUsedAbonnements(): array
{
    return $this->createQueryBuilder('au')
        ->select('au.id_abonnement, COUNT(au.id_abonnement) as utilisateurs_count')
        ->groupBy('au.id_abonnement')
        ->orderBy('utilisateurs_count', 'DESC')
        ->getQuery()
        ->getResult();
}


public function findLeastUsedAbonnements(int $limit = 5): array
{
    return $this->createQueryBuilder('au')
        ->select('au.id_abonnement, COUNT(au.id_abonnement) as utilisateurs_count')
        ->groupBy('au.id_abonnement')
        ->orderBy('utilisateurs_count', 'ASC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}



//    /**
//     * @return Abonnement[] Returns an array of Abonnement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Abonnement
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
