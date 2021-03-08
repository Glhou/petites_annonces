<?php

namespace App\Controller;

use App\Entity\AdSearch;
use App\Entity\User;
use App\Form\AdSearchType;
use App\Form\RoleUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @IsGranted ("ROLE_ADMIN")
 * @Route("/admin")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request)
    {
        $adRepository = $this->getDoctrine()->getRepository("App\Entity\Ad");
        $userRepository = $this->getDoctrine()->getRepository("App\Entity\User");

        // chiffres clés
        $adNumber = $adRepository->numberOfAd();
        $resoAdNumber = $adRepository->numberOfResolvedAd();
        $unresoAdNumber = $adRepository->numberOfUnresolvedAd();
        //utilisateurs
        $users = $userRepository->allUsers();

        return $this->render('admin/index.html.twig', [
            "adNumber"=>$adNumber,
            "resoAdNumber"=>$resoAdNumber,
            "unresoAdNumber"=>$unresoAdNumber,
            "users" => $users,
        ]);
    }

    /**
     * @Route ("/user/{id}",name="gestionUser")
     * @IsGranted ("ROLE_ADMIN")
     */
    public function gestionUser($id){
        $adRepository = $this->getDoctrine()->getRepository("App\Entity\Ad");
        $commentRepository = $this->getDoctrine()->getRepository("App\Entity\Comment");
        $userRepository = $this->getDoctrine()->getRepository("App\Entity\User");


        $user=$userRepository->getUserById($id)[0];

        $adUser = $adRepository->adByUser($user);
        $commentUser = $commentRepository->commentByUser($user);



        return $this->render('admin/user_gestion.html.twig', [
            "adUser" => $adUser,
            "commentUser" => $commentUser,
            "user" =>$user,
        ]);
    }

    /**
     * @Route ("/del-user/{id}",name="del_user")
     * @IsGranted ("ROLE_ADMIN")
     */
    public function delUser($id){
        $manager = $this->getDoctrine()->getManager();
        $adRepository = $this->getDoctrine()->getRepository("App\Entity\Ad");
        $userRepository = $this->getDoctrine()->getRepository("App\Entity\User");
        $commentRepository = $this->getDoctrine()->getRepository("App\Entity\Comment");

        $user=$userRepository->getUserById($id)[0];// on récupère le user à supprimer

        // on récupère toutes les ads pour les supprimer
        $ads = $adRepository->AdByUser($user);
        for ($i=0;$i<count($ads);$i++){
            $ad = $ads[$i];
            // on récupère tout les commentaires pour les supprimer
            $currentComments = $commentRepository->findByDateWithAd($ad);
            for ($j = 0; $j < count($currentComments); $j++) {
                // on supprime un a un les commentaires
                $manager->remove($currentComments[$j]);
            }
            // on supprime ensuite l'annonce
            $manager->remove($ads[$i]);
        }
        // puis on supprime tout ses commentaires
        $currentComments = $commentRepository->commentByUser($user);
        for ($i = 0; $i < count($currentComments); $i++) {
            // on supprime un a un les commentaires
            $manager->remove($currentComments[$i]);
        }
        dump($adRepository->adByUser($user));
        dump($commentRepository->commentByUser($user));

        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute("admin");
    }


    /**
     * @Route ("/edit-role/{id}",name="edit_role")
     * @IsGranted ("ROLE_ADMIN")
     */
    public function editRole(Request $request,$id){

        $adRepository = $this->getDoctrine()->getRepository("App\Entity\Ad");
        $userRepository = $this->getDoctrine()->getRepository("App\Entity\User");

        // chiffres clés
        $adNumber = $adRepository->numberOfAd();
        $resoAdNumber = $adRepository->numberOfResolvedAd();
        $unresoAdNumber = $adRepository->numberOfUnresolvedAd();
        //utilisateurs
        $users = $userRepository->allUsers();


        $manager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository("App\Entity\User");

        $user = $userRepository->getUserById($id)[0];

        $form = $this->createForm(RoleUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("admin");
        }

        return $this->render('admin/index.html.twig', [
            "adNumber"=>$adNumber,
            "resoAdNumber"=>$resoAdNumber,
            "unresoAdNumber"=>$unresoAdNumber,
            "users" => $users,
            "form" => $form->createView(),
            "user" => $user,
        ]);
    }
}
