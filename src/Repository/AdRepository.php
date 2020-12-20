<?php

namespace App\Repository;

use App\Entity\Ad;
use App\Entity\AdSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Migrations\Query\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    public function findAllByDate()
    {

        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllByDateQuery(): \Doctrine\ORM\Query
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ;
    }

    public function findAllByDateQuerySearch(AdSearch $search): \Doctrine\ORM\Query
    {
        $query = $this->createQueryBuilder('a')
            ->orderBy('a.date','DESC');

        if($search->getName()){
            $query = $query
                ->andWhere("a.title LIKE :name")
                ->setParameter("name",$search->getName()."%");
        }
        if($search->getType()){
            $query = $query
                ->andWhere("a.type = :type")
                ->setParameter("type",$search->getType());
        }

        return $query->getQuery();
    }

    public function find1ByDate()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->andWhere('a.type = 1')
            ->getQuery()
            ->getResult()
            ;
    }

    public function find2ByDate()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->andWhere('a.type = 2')
            ->getQuery()
            ->getResult()
            ;
    }

    public function find3ByDate()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->andWhere('a.type = 3')
            ->getQuery()
            ->getResult()
            ;
    }

    public function find4ByDate()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->Where('a.type = 4')
            ->getQuery()
            ->getResult()
            ;
    }

    public function find5ByDate()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->andWhere('a.type = 5')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Ad[] Returns an array of Ad objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ad
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
