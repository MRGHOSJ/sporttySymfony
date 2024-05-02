<?php

namespace App\Repository;

use App\Entity\AbonnementUtilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AbonnementUtilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbonnementUtilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbonnementUtilisateur[]    findAll()
 * @method AbonnementUtilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementUtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbonnementUtilisateur::class);
    }

    public function hasUsers($abonnementId)
    {
        return $this->createQueryBuilder('au')
            ->andWhere('au.abonnement = :abonnementId')
            ->setParameter('abonnementId', $abonnementId)
            ->getQuery()
            ->getResult();
    }
}
