<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductsCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductsCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductsCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductsCategories[]    findAll()
 * @method ProductsCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductsCategories::class);
    }

    /**
    * @return Product Returns an array of Product objects
    */
    // Recherche si un ProductsCategories existe
    public function isProductsCategories(int $product_Id, int $categories_Id)
    {
        $result = $this->createQueryBuilder('p')
        ->where('p.product = :product')
        ->setParameter('product', $product_Id)
        ->andWhere('p.categories = :categories')
        ->setParameter('categories', $categories_Id)
        ->getQuery()
        ->getResult();

        if(!isset($result[0])){
            return NULL;
        }else{
            return $result[0];
        }
    }

    /**
    * @return Product[] Returns an array of Product objects
    */
    // Recherche si un ProductsCategories existe
    public function getCategoriesFromProduct(Product $product)
    {
        $result = $this->createQueryBuilder('p')
        ->where('p.product = :product')
        ->setParameter('product', $product)
        ->getQuery()
        ->getResult();

        if(!isset($result)){
            return NULL;
        }else{
            return $result;
        }
    }
}
