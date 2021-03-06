<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
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



     /**
      * @return Category[] Returns an array of Category objects
      */
    public function getArticles($condition = [])
    {

        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.categories','c')
            ->addSelect('c')
        ;
        return $this->_getWhere($qb,$condition)
            ->getQuery()
            ->getResult()
        ;
    }

    private function _getWhere($qb,array $condition = [])
    {
        if(isset($condition['category_id']) && !empty($condition['category_id']))
        {
            $qb->andWhere('c.id = :cId')->setParameter('cId',$condition['category_id']);
        }

        if(isset($condition['title']) && !empty($condition['title']))
        {
            $qb->andWhere('a.title LIKE :title')
                ->setParameter('title', '%"'.$condition['title'].'"%');
        }

        return $qb;
    }
}
