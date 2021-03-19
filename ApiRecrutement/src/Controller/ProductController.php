<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductsCategories;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductsCategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products/all", name="productAll")
     */
    // Recupere tous les Products
    public function all(Request $request, ProductRepository $productRepository): Response
    {
        // liste de tous les products 
        // TODO : Mise en place des parametres de tri (start, limit,search,order,sort)
        $products = $productRepository->findAllProducts();

        $arrayProducts = [];
        foreach ($products as $individual) {
            $arrayProducts[] = [
                $individual
            ];
        }

        return $this->json($arrayProducts, 200);
    }

    /**
     * @Route("/products/add", name="productAdd")
     */
    //Ajoute un Product
    public function add(Request $request,EntityManagerInterface $em, ProductRepository $productRepository, CategoryRepository $categoryRepository , BrandRepository $brandRepository, ProductsCategoriesRepository $productsCategoriesRepository): Response
    {
        $product = new Product();
        $product->setName($request->get('name'));
        $product->setActive($request->get('active'));
        $product->setDescription($request->get('description'));
        $product->setUrl($request->get('url'));

        // Recuperation de l'objet Brand
        $brand = $brandRepository->isFoundBrand($request->get('brand'));
        $product->setBrand($brand);

        $em->persist($product);
        $em->flush();

        // Recuperation de la séléction de catégories sous forme d'un tableau classique
        $categoriesList = [];
        $categoriesRequest = json_decode($request->get('categories'), true);
        foreach ($categoriesRequest as $category_id) {
            // Test pour déterminer si l'entrée existe
            
            if($productsCategoriesRepository->isProductsCategories($product->getId(),$category_id) == NULL){
                $productsCategories = new ProductsCategories;
                $productsCategories->setProduct($product);

                //Récupération de l'objet category
                $category = $categoryRepository->isFoundCategory($category_id);
                $productsCategories->setCategories($category);

                $em->persist($productsCategories);
                $em->flush();
            }
        }  

        // TODO : liste de toutes les categories

        return $this->json(['Product create', $product],201 );
    }

    /**
     * @Route("/products/delete/{id}", name="productDelete")
     */
    // Supprime un Product
    public function delete(Request $request,EntityManagerInterface $em, ProductRepository $productRepository): Response
    {
        $product = $productRepository->isFoundProduct($request->get('id'));
        if(isset($product)){
            $em->remove($product);
            $em->flush();
            return $this->json(['Product delete'],200 );
        } else {
            return $this->json(['Product not found'],404 );
        } 
    }

    /**
     * @Route("/products/edit/{id}", name="productEdit")
     */
    // Edite un Product
    public function edit(Request $request,EntityManagerInterface $em, ProductRepository $productRepository, CategoryRepository $categoryRepository , BrandRepository $brandRepository, ProductsCategoriesRepository $productsCategoriesRepository): Response
    {
        $product = $productRepository->isFoundProduct($request->get('id'));
        if(isset($product)){

            $product->setName($request->get('name'));
            $product->setDescription($request->get('description'));
            $product->setUrl($request->get('url'));

            // Recuperation de l'objet Brand
            $brand = $brandRepository->isFoundBrand($request->get('brand'));
            $product->setBrand($brand);

            $em->persist($product);
            $em->flush();
            
            // Recuperation de la séléction de catégories sous forme d'un tableau classique
            $categoriesList = [];
            $categoriesRequest = json_decode($request->get('categories'), true);
            foreach ($categoriesRequest as $category_id) {
                // Test pour déterminer si l'entrée existe
                
                if($productsCategoriesRepository->isProductsCategories($product->getId(),$category_id) == NULL){
                    $productsCategories = new ProductsCategories;
                    $productsCategories->setProduct($product);

                    //Récupération de l'objet category
                    $category = $categoryRepository->isFoundCategory($category_id);
                    $productsCategories->setCategories($category);

                    $em->persist($productsCategories);
                    $em->flush();
                }
            }

            // TODO : liste de toutes les categories

            return $this->json(['Product edited',
            $product
            ],200 );
        } else {
            return $this->json(['Product not found'],404 );
        }
    }

    /**
     * @Route("/products/{id}", name="productOne")
     */
    // Recupere tous les Products
    public function one(Request $request, ProductRepository $productRepository): Response
    {
        $product = $productRepository->isFoundProduct($request->get('id'));

        if(isset($product)){

            // TODO : liste de toutes les categories

            return $this->json(['product found',
            $product
            ],200 );

        } else {
            return $this->json(['product not found'],404 );
        }
    }

}