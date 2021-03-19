<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class BrandController extends AbstractController
{
    /**
     * @Route("/brand/all", name="brandAll")
     */
    // Recupere tous les brands
    public function all(Request $request, BrandRepository $brandRepository): Response
    {
        // liste de tous les brands
        $brands = $brandRepository->findAllBrand();
        // Test si $brand est vide
        if(isset($brands)){

            $arrayBrands = [];

            foreach ($brands as $individual) {
                $arrayBrands[] = [
                    $individual
                ];
            }
            return $this->json(['Brands found', $arrayBrands], 200);

        }else{
            return $this->json('Brands not found', 200);
        }
    }

    /**
     * @Route("/brand/add", name="brandAdd")
     */
    //Ajoute un brands
    public function add(Request $request,EntityManagerInterface $em): Response
    {
        $brand = new Brand();
        $brand->setName($request->get('name'));
        $em->persist($brand);
        $em->flush();
        return $this->json(['Success',$brand],200 );
    }

    /**
     * @Route("/brand/delete/{id}", name="brandDelete")
     */
    // Supprime un brand 
    // TODO : Suppression Cascade
    public function delete(Request $request,EntityManagerInterface $em, BrandRepository $brandRepository): Response
    {
        $brand = $brandRepository->isFoundBrand($request->get('id'));
        if(isset($brand)){
            $em->remove($brand);
            $em->flush();
            return $this->json(['Brand delete'],200 );
        } else {
            return $this->json(['Brand not found'],404 );
        } 
    }

    /**
     * @Route("/brand/edit/{id}", name="brandEdit")
     */
    // Edite un brands
    public function edit(Request $request,EntityManagerInterface $em, BrandRepository $brandRepository): Response
    {
        $brand = $brandRepository->isFoundBrand($request->get('id'));
        if(isset($brand)){
            $brand->setName($request->get('name'));
            $em->persist($brand);
            $em->flush();
            return $this->json(['Brand edited',$brand->getName(),$brand->getId()],200 );
        } else {
            return $this->json(['Brand not found'],404 );
        }
    }
}
