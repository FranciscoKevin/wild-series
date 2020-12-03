<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categories", name="category_")
 */

 class CategoryController extends AbstractController
 {
     /**
      * Show all rows from Category's entity
      *
      * @Route("/", name="index")
      * @return Reponse
      */
      public function index(): Response
      {
          $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        return $this->render(
            'category/index.html.twig', [
                "categories" => $categories,
        ]);   
      }

    /**
     * @Route("/{categoryName}", methods={"GET"}, name="show")
     */
    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);
        if(!$category) {
            throw $this->createNotFoundException(
                'The category with name : '. $categoryName . 'does not exist'
            );
        }

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ["category" => $category],
                ["id" => "DESC"],
                3
            );

        if(!$programs) {
            throw $this->createNotFoundException(
                'The program with category name : '. $categoryName . 'does not exist'
            );
        }

        return $this->render('category/show.html.twig', [
            "category" => $category,
            "programs" => $programs,    
        ]);
    }
 }
