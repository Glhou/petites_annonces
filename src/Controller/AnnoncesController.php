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
    public function index(AdRepository $repoAd, CommentRepository $repoComment): Response
    {
        $ad = $repoAd->findAll();
        $comment = $repoComment->findAll();
        return $this->render('annonces/index.html.twig', [
            'Ad' => $ad, 'Comment' => $comment,
        ]);
    }

    
}
