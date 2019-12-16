<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Article;
use App\Entity\User;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CommentController extends AbstractController
{
    /**
     * @Route("/comment/list", name="comment_index")
     */
    public function index()
    {
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findAll();
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/comment/edit/{id}", name="comment_edit")
     */
    public function edit($id, Request $request, EntityManagerInterface $manager)
    {
        if ($id > 0)
            $comment = $manager->getRepository(Comment::class)->find($id);
        else
            $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setCreatedat(new \DateTime());
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('comment_index');
        }
        return $this->render('comment/edit.html.twig', [
            'formComment' => $formComment->createView(),
            'id' => $comment->getId() ? $article->getId() : 0
        ]);
    }

    /**
     * @Route("/comment/delete/{id}", name="comment_delete")
     */
    public function delete($id)
    {
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($comment);
        $manager->flush();
        return $this->redirectToRoute('comment_index', [
            'id' => $comment->getId(),
        ]);
    }
}