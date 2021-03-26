<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    public function allUsers(){
        return $this->createQueryBuilder("a")
            ->orderBy("a.username")
            ->getQuery()
            ->getResult();
    }

    public function getUserById(Int $id){
        return $this->createQueryBuilder("a")
            ->where("a.id =:id")
            ->setParameter("id",$id)
            ->getQuery()
            ->getResult();
    }

    public function nbOfUsers(){
        $q = $this->createQueryBuilder("a");
        return $q->select($q->expr()->count('a'))
            ->getQuery()
            ->getSingleScalarResult();
    }
}
