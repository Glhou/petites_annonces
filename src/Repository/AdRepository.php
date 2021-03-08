<?php

namespace App\Repository;

use App\Entity\Ad;
use App\Entity\AdSearch;
use App\Entity\User;
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
            ->setMaxResults(5)
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
        if($search->getResolved() == false){
            $query = $query
                ->andWhere("a.resolved = false");
        }

        return $query->getQuery();
    }

    public function find1ByDate()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->andWhere('a.type = 1')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    public function find2ByDate()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->andWhere('a.type = 2')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    public function find3ByDate()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->andWhere('a.type = 3')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    public function find4ByDate()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->Where('a.type = 4')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    public function find5ByDate()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->andWhere('a.type = 5')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findLastByUser(User $user){
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->andWhere('a.author = :user')
            ->setParameter("user",$user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function numberOfAd(){
        $q = $this->createQueryBuilder('a');
        $q-> select($q->expr()->count('a'));
        return $q->getQuery()->getSingleScalarResult();
    }

    public function numberOfResolvedAd(){
        $q = $this->createQueryBuilder('a');
        $q-> select($q->expr()->count('a'))
        ->where("a.resolved = true");
        return $q->getQuery()->getSingleScalarResult();
    }

    public function numberOfUnresolvedAd(){
        $q = $this->createQueryBuilder('a');
        $q-> select($q->expr()->count('a'))
            ->where("a.resolved = false");
        return $q->getQuery()->getSingleScalarResult();
    }

    public function adByUser(User $User){
        return $this->createQueryBuilder("a")
            ->where('a.author = :user')
            ->setParameter("user",$User)
            ->getQuery()
            ->getResult();
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
