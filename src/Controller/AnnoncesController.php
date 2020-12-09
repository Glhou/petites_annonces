<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ad;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\AdRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;


class AnnoncesController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces")
     */
    public function index(AdRepository $repo): Response
    {
        $ad = $repo->findAll();
        return $this->render('annonces/index.html.twig', [
            'Ad' => $ad,
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('home.html.twig', [
        ]);
    }
}
