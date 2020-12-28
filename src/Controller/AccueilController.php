<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class AccueilController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil",name="accueil")
     */
    public function accueil()
    {
        $adRepository = $this->getDoctrine()->getRepository("App\Entity\Ad");

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
            'AdAll' => $adAll,
            'Ad1' => $ad1,
            'Ad2' => $ad2,
            'Ad3' => $ad3,
            'Ad4' => $ad4,
            'Ad5' => $ad5,
            'AdUser' => $adLastUser,
            "CountAll" => $CountAll,
            "CountResolved" => $CountResolved,
            "CountUnresolved" => $CountUnresolved,
        ]);
    }
}
