<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function countAllClients(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.role = :role')
            ->setParameter('role', 'ADHERANT')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function hasSubscription($userId)
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.abonnements', 'a')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }
    
    public function findAllSortedByRole($order = 'asc')
    {
        $qb = $this->createQueryBuilder('u');
        $qb->leftJoin('u.roles', 'r') 
        
            ->orderBy('r.name', $order); 
    
        return $qb->getQuery()->getResult();
    }
    public function findByUsernameStartingWith($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.nom LIKE :username')
            ->setParameter('username', $username.'%')
            ->getQuery()
            ->getResult();
    }
    public function findByPrenomStartingWith($prenom)
    {
        return $this->createQueryBuilder('u')
            ->where('u.prenom LIKE :prenom')
            ->setParameter('prenom', $prenom.'%')
            ->getQuery()
            ->getResult();
    }

    public function findByEmailStartingWith($email)
    {
        return $this->createQueryBuilder('u')
            ->where('u.email LIKE :email')
            ->setParameter('email', $email.'%')
            ->getQuery()
            ->getResult();
    }
    
//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
