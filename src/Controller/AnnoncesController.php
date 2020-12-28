<?php

namespace App\Controller;

use App\Entity\AdSearch;
use App\Form\AdSearchType;
use App\Form\AdType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\DependencyInjection\Configuration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ad;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\AdRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * Class AnnoncesController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 * @Route("/annonces")
 */
class AnnoncesController extends AbstractController
{
    // page avec toutes les annonces à la chaine (sans commentaires)
    /**
     * @Route("/", name="annonces")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $adRepository = $this->getDoctrine()->getRepository("App\Entity\Ad");

        $search = new AdSearch();
        $search->setResolved(false);
        $form = $this->createForm(AdSearchType::class, $search);
        $form->handleRequest($request);

        $adAll = $paginator->paginate(
            $adRepository->findAllByDateQuerySearch($search), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );;//On prend toutes les annonces de la plus récente à la plus ancienne


        return $this->render('annonces/index.html.twig', [
            'AdAll' => $adAll, 'form' => $form->createView(),
        ]);
    }

    // page de création et de modification d'une annonce

    /**
     * @Route("/new-annonce", name="new_annonce")
     * @Route("/edit/{id}",name="modif_annonce")
     * @Security ("is_granted('ANNONCE_EDIT', ad) or ad == null")
     */
    public function newAnnonce(?Ad $ad,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        if ($ad == null){
            $ad = new Ad();
        }
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ajout de la date, de l'user et de resolved
            $user = $this->getUser();; // Récupère le user
            $ad->setAuthor($user);
            $ad->setDate();
            $ad->setResolved(false);
            $manager->persist($ad);
            $manager->flush();
            return $this->redirectToRoute("annonces_show", ["id" => $ad->getId()]);
        }


        return $this->render('annonces/newAnnonce.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    //supprime une annonce

    /**
     * @Route("/delete/{id}",name="del_annonce")
     * @Security ("is_granted('ANNONCE_EDIT', ad)")
     */
    public function delAd(Ad $ad)
    {
        $manager = $this->getDoctrine()->getManager();
        $commentRepository = $this->getDoctrine()->getRepository("App\Entity\Comment");

        // on récupère tout les commentaires pour les supprimer
        $currentComments = $commentRepository->findByDateWithAd($ad);
        for ($i = 0; $i < count($currentComments); $i++) {
            // on supprime un a un les commentaires
            $manager->remove($currentComments[$i]);
        }
        // on supprime ensuite l'annonce
        $manager->remove($ad);
        $manager->flush();
        return $this->redirectToRoute('accueil');
    }


    // page d'une annonces avec les commentaires et un truc pour en ajouter

    /**
     * @Route("/{id}", name="annonces_show")
     */
    public function show(Ad $ad, Request $request, PaginatorInterface $paginator)
    {
        $manager = $this->getDoctrine()->getManager();
        $repoComment = $this->getDoctrine()->getRepository("App\Entity\Comment");

        $comments = $paginator->paginate(
            $repoComment->findByDateWithAdQuery($ad), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        ); // commentaire par date décroissante comme ça on affiche en premier les dernier commentaires

        // gestion du formulaire pour nouveau commentaire
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        // ajout de la date et de l'user
        $user = $this->get('security.token_storage')->getToken()->getUser();; // Récupère le user
        $comment->setAuthor($user);
        $comment->setDate();
        $comment->setAd($ad);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute("annonces_show", [
                'id' => $ad->getId(),
            ]);
        }

        return $this->render('annonces/show.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad,
            'comments' => $comments,
        ]);
    }

    //supprime un commentaire

    /**
     * @Route("/commentaire/{id}",name="del_com")
     * @Security ("is_granted('COMMENT_EDIT', comment)")
     */
    public function delCom(Comment $comment)
    {
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($comment);
        $manager->flush();
        return $this->redirectToRoute('annonces_show', [
            'id' => $comment->getAd()->getId(),
        ]);
    }


}
