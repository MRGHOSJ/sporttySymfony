<?php

namespace App\Repository;

use App\Entity\SaleDeSport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SaleDeSport>
 *
 * @method SaleDeSport|null find($id, $lockMode = null, $lockVersion = null)
 * @method SaleDeSport|null findOneBy(array $criteria, array $orderBy = null)
 * @method SaleDeSport[]    findAll()
 * @method SaleDeSport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleDeSportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaleDeSport::class);
    }

//    /**
//     * @return SaleDeSport[] Returns an array of SaleDeSport objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SaleDeSport
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
