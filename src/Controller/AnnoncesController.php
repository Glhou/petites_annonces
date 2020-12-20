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
 */
class AnnoncesController extends AbstractController
{
    // page avec toutes les annonces à la chaine (sans commentaires)
    /**
     * @Route("/annonces", name="annonces")
     */
    public function index(Request $request, AdRepository $adRepository, PaginatorInterface $paginator, CommentRepository $commentRepository): Response
    {
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

    // page de création d'une annonce

    /**
     * @Route("/new-annonce", name="new_annonce")
     */
    public function newAnnonce(Request $request, EntityManagerInterface $manager)
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ajout de la date, de l'user et de resolved
            $user = $this->get('security.token_storage')->getToken()->getUser();; // Récupère le user
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


    //modification d'une annonce

    /**
     * @Route("/annonces/{id}/edit",name="modif_annonce")
     * @Security ("is_granted('ANNONCE_EDIT', ad)")
     */
    public function modifAnnonce(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on ajoute les donnés
            $data = $form->getData();
            $ad->setTitle($data->getTitle() . " ");// on ajoute un espace sinon il grogne parceque le titre
            // existe déjà pour cet user, du coup l'espace change le titre et on le retrouve uniquement
            // (une fois / ça se cumule pas) dans le formulaire.
            $ad->setResolved($data->getResolved());
            $ad->setDescription($data->getDescription());
            $ad->setLocation($data->getLocation());
            $ad->setType($data->getType());
            $manager->persist($ad);
            $manager->flush();
            return $this->redirectToRoute('annonces_show', [
                'id' => $ad->getId(),
            ]);
        }

        return $this->render('annonces/newAnnonce.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    //supprime un commentaire
    /**
     * @Route("/commentaire/{id}",name="del_com")
     * @Security ("is_granted('COMMENT_EDIT', comment)")
     */
    public function delCom(Comment $comment,EntityManagerInterface $manager){
        $manager->remove($comment);
        $manager->flush();
        return $this->redirectToRoute('annonces_show', [
            'id' => $comment->getAd()->getId(),
        ]);
    }

    // page d'une annonces avec les commentaires et un truc pour en ajouter

    /**
     * @Route("/annonces/{id}", name="annonces_show")
     */
    public function show(Ad $ad, CommentRepository $repoComment, EntityManagerInterface $manager, Request $request, PaginatorInterface $paginator)
    {
        $comments = $paginator->paginate(
            $repoComment->findByDateWithIdQuery($ad), /* query NOT result */
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
        }

        return $this->render('annonces/show.html.twig', [
            'form' => $form->createView(), 'ad' => $ad, 'comments' => $comments,
        ]);
    }


    /**
     * @Route("/accueil",name="accueil")
     */
    public function accueil(AdRepository $adRepository)
    {
        // pour les dernier trucs de l'user
        $adLastUser = $adRepository->findLastByUser($this->get('security.token_storage')->getToken()->getUser());

        // pour block centrale
        $adAll = $adRepository->findAllByDate();
        $ad1 = $adRepository->find1ByDate();
        $ad2 = $adRepository->find2ByDate();
        $ad3 = $adRepository->find3ByDate();
        $ad4 = $adRepository->find4ByDate();
        $ad5 = $adRepository->find5ByDate();

        // pour les status à droite
        $CountAll = $adRepository->numberOfAd();
        $CountResolved = $adRepository->numberOfResolvedAd();
        $CountUnresolved = $adRepository->numberOfUnresolvedAd();

        return $this->render('annonces/accueil.html.twig', [
            'AdAll' => $adAll, 'Ad1' => $ad1, 'Ad2' => $ad2, 'Ad3' => $ad3, 'Ad4' => $ad4, 'Ad5' => $ad5, 'AdUser' => $adLastUser, "CountAll" => $CountAll, "CountResolved" => $CountResolved, "CountUnresolved" => $CountUnresolved,
        ]);
    }

}
