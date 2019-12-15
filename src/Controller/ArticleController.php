<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{
    /**
     * @Route("/article/list", name="article_index")
     */
    public function index()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article/edit/{id}", name="article_edit")
     */
    public function edit($id, Request $request, EntityManagerInterface $manager)
    {
        if ($id > 0)
            $article = $manager->getRepository(Article::class)->find($id);
        else
            $article = new Article();
        $formArticle = $this->createForm(ArticleType::class, $article);
        $formArticle->handleRequest($request);
        if ($formArticle->isSubmitted() && $formArticle->isValid()) {
            $article->setCreatedat(new \DateTime());
            $article->setUpdatedat(new \DateTime());
            $article->setClosedat(new \DateTime());
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('article_index');
        }
        return $this->render('article/edit.html.twig', [
            'formArticle' => $formArticle->createView(),
            'id' => $article->getId() ? $article->getId() : 0
        ]);
    }

    /**
     * @Route("/article/delete/{id}", name="article_delete")
     */
    public function delete($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($article);
        $manager->flush();
        return $this->redirectToRoute('article_index', [
            'id' => $article->getId(),
        ]);
    }
}