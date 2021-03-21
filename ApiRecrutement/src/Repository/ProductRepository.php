<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
    * @return Product Returns an array of Product objects
    */
    // Recherche si un Product existe
    public function isFoundProduct(int $id)
    {
        $result = $this->createQueryBuilder('p')
        ->where('p.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getResult();

        if(!isset($result[0])){
            return NULL;
        }else{
            return $result[0];
        }
    }

    /**
    * @return Product[] Returns an array of Product individual list all Product
    */
    public function findAllProducts()
    {  
        $result = $this->findAll();

        return $result;
    }

    /**
    * @return Product[] Returns an array of Product individual from limited value
    */
    public function test(int $limit, string $search, string $order, string $sort)
    {
        $result = $this->createQueryBuilder('p')
        ->where('p.name LIKE :search')
        ->setParameter('search', '%'.$search.'%')
        ->setMaxResults($limit)
        ->add('orderBy', $order .' '. $sort)
        ->getQuery()
        ->getResult();

        return $result;
    }

    /**
    * @return int Returns number of product
    */
    public function howManyProducts()
    {  
        $result = $this->createQueryBuilder('p')
        ->select('count(p.id)')
        ->getQuery()
        ->getSingleScalarResult();

        return $result;
    }
}
