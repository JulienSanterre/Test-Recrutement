<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    /**
     * @Route("/categories/all", name="categoryAll")
     */
    // Recupere tous les categories
    public function all(Request $request, CategoryRepository $categoryRepository): Response
    {
        // liste de tous les categories
        $categories = $categoryRepository->findAllCategories();
        if (isset($categories)) {
            $arrayCategories = [];

            foreach ($categories as $individual) {
                $arrayCategories[] = [
                    $individual
                ];
            }
            return $this->json($arrayCategories, 200);
        }else{
            return $this->json('Categories not found', 400);
        }
    }

    /**
     * @Route("/categories/add", name="categoryAdd")
     */
    //Ajoute un category
    public function add(Request $request,EntityManagerInterface $em): Response
    {
        $category = new Category();
        $category->setName($request->get('name'));
        $em->persist($category);
        $em->flush();
        return $this->json(['Success', $category],200 );
    }

     /**
     * @Route("/categories/edit/{id}", name="categoryEdit")
     */
    // Edite un category
    public function edit(Request $request,EntityManagerInterface $em, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->isFoundCategory($request->get('id'));
        if(isset($category)){
            $category->setName($request->get('name'));
            $em->persist($category);
            $em->flush();
            return $this->json(['Category edited',$category->getName(),$category->getId()],200 );
        } else {
            return $this->json(['Category not found'],404 );
        }
    }

     /**
     * @Route("/categories/delete/{id}", name="categoryDelete")
     */
    // Supprime un category
    // TODO : Suppression Cascade
    public function delete(Request $request,EntityManagerInterface $em, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->isFoundCategory($request->get('id'));
        if(isset($category)){
            $em->remove($category);
            $em->flush();
            return $this->json(['Category delete'],200 );
        } else {
            return $this->json(['Category not found'],404 );
        } 
    }
}
