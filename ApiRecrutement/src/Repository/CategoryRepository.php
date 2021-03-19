<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
    * @return Category Returns an array of Category objects
    */
    // Recherche si un Category existe
    public function isFoundCategory(int $id)
    {
        $result = $this->createQueryBuilder('c')
        ->where('c.id = :id')
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
    * @return Category[] Returns an array of Category individual list all Category
    */
    public function findAllCategories()
    {  
        $result = $this->findAll();
        return $result;
    }
}
