<?php

namespace App\Repository;

use App\Entity\Cabine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cabine>
 *
 * @method Cabine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cabine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cabine[]    findAll()
 * @method Cabine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CabineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cabine::class);
    }

//    /**
//     * @return Cabine[] Returns an array of Cabine objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cabine
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
