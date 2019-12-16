<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home_index")
     * @Route("/", name="home_default")
     */
    public function index()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'categories' => $categories,
        ]);
    }

       /**
     * @Route("/gagne", name="home_gagne")
     * @isGranted("ROLE_USER")
     */
    public function gagne()
    {
        return $this->render('home/gagne.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
