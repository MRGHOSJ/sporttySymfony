<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

//    /**
//     * @return Reclamation[] Returns an array of Reclamation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reclamation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
/*
public function countReclamationsByType(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.nom, COUNT(r.id) as count')
            ->groupBy('r.nom')
            ->getQuery()
            ->getResult();
    }*/
    public function countReclamationsByType(): array
    {
        $types = [
            'Payment Issue',
            'Equipment Problem',
            'Discomfort in Facilities',
            'Security Issue',
            'Reservation Issue',
            'Capacity Issue in Class',
            'Improvement Suggestions',
            'Others'
        ];

        $counts = [];

        foreach ($types as $type) {
            $counts[$type] = $this->createQueryBuilder('r')
                ->select('COUNT(r.id)')
                ->where('r.nom = :type')
                ->setParameter('type', $type)
                ->getQuery()
                ->getSingleScalarResult();
        }

        return $counts;
    }
    
    public function findBySearchAndSort($searchBy, $searchQuery, $sortBy, $sortOrder)
    {
        $qb = $this->createQueryBuilder('r');

        if ($searchQuery && $searchBy) {
            $qb->andWhere('r.'.$searchBy.' LIKE :searchQuery')
            ->setParameter('searchQuery', '%'.$searchQuery.'%');
        }

        $qb->orderBy('r.'.$sortBy, $sortOrder);

        return $qb->getQuery()->getResult();
    }
}
