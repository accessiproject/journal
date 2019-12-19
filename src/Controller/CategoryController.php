<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Image;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile; 


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
    public function edit($id, Request $request, FileUploader $fileUploader, EntityManagerInterface $manager)
    {
        if ($id > 0)
            $category = $manager->getRepository(Category::class)->find($id);
        else
            $category = new Category();
        $formCategory = $this->createForm(CategoryType::class, $category);
        $formCategory->handleRequest($request);
        if ($formCategory->isSubmitted() && $formCategory->isValid()) {
            
            dump($post);    
            $image = new Image();
                /** @var UploadedFile $imageFile */
            $imageFile = $formCategory['image']['imageFilename']->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $image->setImageFilename($imageFileName);
                $image->setAlt($formCategory['alt']->getData());
                $image->setAddat(new \DateTime());
                $image->setCategory($category);
                $manager->persist($image);
                $manager->flush();            
            }
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

    /**
     * @Route("/theme/list/article/{id}", name="category_article")
     */
    public function article($id)
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findByCategory($id);
        return $this->render('category/article.html.twig', [
            'controller_name' => 'CategoryController',
            'articles' => $articles,
        ]);
    }

}