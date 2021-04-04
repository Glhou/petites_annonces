<?php

namespace App\Command;

use App\Entity\Ad;
use App\Repository\AdRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClearResolvedCommand extends Command
{
    private $aRepo;
    private $em;
    private $cRepo;

    protected static $defaultName = 'ClearResolvedCommand';

    public function __construct(AdRepository $adRepository, CommentRepository $commentRepository, EntityManagerInterface $manager, string $name = null)
    {
        $this->aRepo = $adRepository;
        $this->cRepo = $commentRepository;
        $this->em = $manager;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Cette commande permet de supprimer les commentaire résulus depuis un certain temps')
        ;
    }

    private function delAd(Ad $ad){
        // on récupère tout les commentaires pour les supprimer
        $currentComments = $this->cRepo->findByDateWithAd($ad);
        for ($i = 0; $i < count($currentComments); $i++) {
            // on supprime un a un les commentaires
            $this->em->remove($currentComments[$i]);
        }
        // on supprime ensuite l'annonce
        $this->em->remove($ad);
        $this->em->flush();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $resolveds = $this->aRepo->oldResolvedAd();

        foreach($resolveds as $resolved) {
            $this->delAd($resolved);
        }

        $io->success('Suppression réussie');

        return 0;
    }
}
