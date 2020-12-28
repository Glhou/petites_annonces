<?php

namespace App\Security\Voter;

use App\Entity\Ad;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AnnonceVoter extends Voter
{
    const ANNONCE_EDIT = "ANNONCE_EDIT";
    private $security;

    public function __construct(Security $security){
        $this->security = $security;
    }

    protected function supports($attribute, $ad)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::ANNONCE_EDIT])
            && $ad instanceof \App\Entity\Ad;
    }

    protected function voteOnAttribute($attribute, $ad, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::ANNONCE_EDIT:
                return ($ad->getAuthor() == $user) || ($this->security->isGranted('ROLE_ADMIN'));

        }

        return false;
    }
}
