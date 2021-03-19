<?php

namespace App\Repository;

use App\Entity\Brand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Brand|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brand|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brand[]    findAll()
 * @method Brand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brand::class);
    }

    /**
    * @return Brand Returns an array of Brand objects
    */
    // Recherche si un Brand existe
    public function isFoundBrand(int $id)
    {
        $result = $this->createQueryBuilder('b')
        ->where('b.id = :id')
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
    * @return Brand[] Returns an array of Brand individual list all Brand
    */
    public function findAllBrand()
    {  
        $result = $this->findAll();
        return $result;
    }
}
