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
    public function all(Request $request, ProductRepository $productRepository, ProductsCategoriesRepository $productsCategoriesRepository): Response
    {
        // liste de tous les products 
        $products = []; 

        // Mise en place des différentes variables pour une séléction plus précises (start, limit,search,order,sort)
        if($request->get('start') != NULL or $request->get('start') == 1){
            $limit = 1;
        }else if($request->get('limit') != NULL){
            $limit = $request->get('limit');
        }else{
            // Limite au maximum d'entrées
            $limit = $productRepository->howManyProducts();
        }

        if($request->get('search') == NULL){
            $search = '';
        }else{
            $search = $request->get('search');
        }

        if(!$request->get('order')){
            $order = 'p.name';
        }else{
            $order = 'p.'.$request->get('order');
        }

        if($request->get('sort') != 'DESC'){
            $sort = 'ASC';
        }else{
            $sort = 'DESC';
        }
        
        $products = $productRepository->test($limit, $search, $order, $sort);
        
        $arrayProducts = [];
        foreach ($products as $individual) {

            // Création de la variable pour récupérer les catégories
            $categoriesList = [];

            // Enregistrement des categories dans la variable
            $productsCategories = $productsCategoriesRepository->getCategoriesFromProduct($individual);
            foreach($productsCategories as $productCategory ){
                $categoriesList [] = $productCategory->getCategories();
            }

            $arrayProducts[] = [
                $individual,$categoriesList
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

                // Création de la variable pour récupérer les catégories
                $categoriesList[] = $category;

                $em->persist($productsCategories);
                $em->flush();
            }
        }

        return $this->json(['Product create', $product, $categoriesList],201 );
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

            // Supprimer les anciennes catégories
            $oldCategories = $productsCategoriesRepository->getCategoriesFromProduct($product);
            foreach($oldCategories as $oldCategory){
                $em->remove($oldCategory);
                $em->flush();
            }

            // Ajout des nouvelles catégories
            foreach ($categoriesRequest as $category_id) {
                $productsCategories = new ProductsCategories;
                $productsCategories->setProduct($product);

                //Récupération de l'objet category
                $category = $categoryRepository->isFoundCategory($category_id);
                $productsCategories->setCategories($category);

                // Création de la variable pour récupérer les catégories
                $categoriesList[] = $productsCategories->getCategories();

                $em->persist($productsCategories);
                $em->flush();
            }

            return $this->json(['Product edited',
            $product, $categoriesList
            ],200 );
        } else {
            return $this->json(['Product not found'],404 );
        }
    }

    /**
     * @Route("/products/{id}", name="productOne")
     */
    // Recupere tous les Products
    public function one(Request $request, ProductRepository $productRepository, ProductsCategoriesRepository $productsCategoriesRepository): Response
    {
        $product = $productRepository->isFoundProduct($request->get('id'));

        // Création de la variable pour récupérer les catégories
        $categoriesList = [];

        if(isset($product)){

            
            // Enregistrement des categories dans la variable
            $productsCategories = $productsCategoriesRepository->getCategoriesFromProduct($product);
            foreach($productsCategories as $productCategory ){
                $categoriesList [] = $productCategory->getCategories();
            }

            return $this->json(['product found',
            $product, $categoriesList
            ],200 );

        } else {
            return $this->json(['product not found'],404 );
        }
    }

}
