<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CategoryController extends AbstractController
{
    /**
     * @Route("/category/list", name="category_index")
     */
    public function index()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/category/edit/{id}", name="category_edit")
     */
    public function edit($id, Request $request, EntityManagerInterface $manager)
    {
        if ($id > 0)
            $category = $manager->getRepository(Category::class)->find($id);
        else
            $category = new Category();
        $formCategory = $this->createForm(CategoryType::class, $category);
        $formCategory->handleRequest($request);
        if ($formCategory->isSubmitted() && $formCategory->isValid()) {
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('category_index');
        }
        return $this->render('category/edit.html.twig', [
            'formCategory' => $formCategory->createView(),
            'id' => $category->getId() ? $category->getId() : 0
        ]);
    }

    /**
     * @Route("/category/delete/{id}", name="category_delete")
     */
    public function delete($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($category);
        $manager->flush();
        return $this->redirectToRoute('category_index', [
            'id' => $category->getId(),
        ]);
    }
}