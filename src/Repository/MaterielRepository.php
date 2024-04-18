<?php

namespace App\Repository;

use App\Entity\Materiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Materiel>
 *
 * @method Materiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Materiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Materiel[]    findAll()
 * @method Materiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materiel::class);
    }

//    /**
//     * @return Materiel[] Returns an array of Materiel objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Materiel
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    // Dans MaterielRepository.php

    public function findAllSortedBy($sort)
    {
        $queryBuilder = $this->createQueryBuilder('materiel');

        switch ($sort) {
            case 'nom':
                $queryBuilder->orderBy('materiel.nom', 'ASC');
                break;
            case 'quantite':
                $queryBuilder->orderBy('materiel.qte', 'ASC');
                break;
            default:
                // Si une option de tri non reconnue est fournie, tri par nom par défaut
                $queryBuilder->orderBy('materiel.nom', 'ASC');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function findBySearchTerm($searchTerm)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.nom LIKE :search')
            ->setParameter('search', '%'.$searchTerm.'%')
            ->orderBy('m.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
