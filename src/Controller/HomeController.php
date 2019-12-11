<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home_index")
     * @Route("/", name="home_default")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
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
