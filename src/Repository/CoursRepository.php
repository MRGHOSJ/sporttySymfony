<?php

namespace App\Repository;

use App\Entity\Cours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cours>
 *
 * @method Cours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cours[]    findAll()
 * @method Cours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cours::class);
    }

    
    public function findBySearchAndSort($searchBy, $searchQuery, $sortBy, $sortOrder)
    {
        $qb = $this->createQueryBuilder('c');

        if ($searchQuery && $searchBy) {
            $qb->andWhere('c.'.$searchBy.' LIKE :searchQuery')
            ->setParameter('searchQuery', '%'.$searchQuery.'%');
        }

        $qb->orderBy('c.'.$sortBy, $sortOrder);

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Cours[] Returns an array of Cours objects
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

//    public function findOneBySomeField($value): ?Cours
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function countCoursByType(): array
    {
        $types = [
            'Individuel',
            'groupe',
            
        ];

        $counts = [];

        foreach ($types as $type) {
            $counts[$type] = $this->createQueryBuilder('c')
                ->select('COUNT(c.idCours)')
                ->where('c.type = :type')
                ->setParameter('type', $type)
                ->getQuery()
                ->getSingleScalarResult();
        }

        return $counts;
    }






}
