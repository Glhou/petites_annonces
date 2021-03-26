<?php

namespace App\Repository;

use App\Entity\Ad;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findByDateWithId(Ad $ad)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->where('a.ad =:ad')
            ->setParameter("ad",$ad)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByDateWithAdQuery(Ad $ad)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->where('a.ad =:ad')
            ->setParameter("ad",$ad)
            ->getQuery()
            ;
    }

    public function findByDateWithAd(Ad $ad)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->where('a.ad =:ad')
            ->setParameter("ad",$ad)
            ->getQuery()
            ->getResult()
            ;
    }
    public function commentByUser(User $User){
        return $this->createQueryBuilder("a")
            ->where('a.author = :user')
            ->setParameter("user",$User)
            ->getQuery()
            ->getResult();
    }

    public function nbCommentByAd(){
        $q = $this->createQueryBuilder("a");
        return $q->select($q->expr()->count('a'))
            ->groupBy("a.ad")
            ->getQuery()
            ->getScalarResult();
    }

    public function nbOfComments(){
        $q = $this->createQueryBuilder("a");
        return $q->select($q->expr()->count('a'))
            ->getQuery()
            ->getSingleScalarResult();
    }



    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
