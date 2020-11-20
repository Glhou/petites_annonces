<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces")
     */
    public function index(): Response
    {
        return $this->render('annonces/index.html.twig', [
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('annonces/home.html.twig', [
        ]);
    }
}
